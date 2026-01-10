#!/usr/bin/env python3
from __future__ import annotations

import argparse
import json
import struct
from pathlib import Path


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Pack JSON files into dungeon.bin with original encryption."
    )
    parser.add_argument(
        "--base",
        dest="base_bin",
        type=Path,
        default=Path("input/dungeon.bin"),
        help="Path to base dungeon.bin (default: input/dungeon.bin)",
    )
    parser.add_argument(
        "--input",
        dest="input_dir",
        type=Path,
        default=Path("input"),
        help="Input directory with JSON files (default: input)",
    )
    parser.add_argument(
        "--output",
        dest="output_bin",
        type=Path,
        default=Path("output/dungeon.bin"),
        help="Output dungeon.bin path (default: output/dungeon.bin)",
    )
    return parser.parse_args()


def decrypt(data: bytes) -> bytes:
    return bytes(b ^ 0xFF for b in data)


def encrypt(data: bytes) -> bytes:
    return bytes(b ^ 0xFF for b in data)


def read_header(bin_path: Path) -> tuple[bytes, list[bytes], list[str], list[int], bytes]:
    data = bin_path.read_bytes()
    header = data[:16]
    _, _, _, count = struct.unpack("<4I", header)

    entries: list[bytes] = []
    names: list[str] = []
    offset = 16
    for _ in range(count):
        entry = data[offset : offset + 260]
        entries.append(entry)
        name = entry[:200].split(b"\x00", 1)[0].decode("ascii")
        names.append(name)
        offset += 260

    offsets_start = offset
    offsets = list(struct.unpack(f"<{count}I", data[offset : offset + count * 4]))
    offset += count * 4

    data_start = offsets[0]
    extra = data[offset:data_start]
    return header, entries, names, offsets, extra


def load_json(path: Path) -> dict:
    return json.loads(path.read_text(encoding="utf-8"))


def format_list(values: list[int]) -> str:
    if not values:
        return "0"
    return "{}\t{}".format(len(values), "\t".join(str(value) for value in values))


def render_dun(payload: dict) -> str:
    spawns = payload.get("spawns", [])
    blocks = payload.get("blocks", [])

    lines = [
        "; [spawn count]",
        str(len(spawns)),
        "; [spawns]",
        "; spawnIndex monsterIndex",
    ]
    for index, value in enumerate(spawns):
        lines.append(f"{index}\t{value}")

    lines.extend(
        [
            ";",
            "; [block count]",
            str(len(blocks)),
        ]
    )

    for index, block in enumerate(blocks, start=1):
        rect = block.get("rect", [0, 0, 0, 0])
        enemies = block.get("enemies", [])
        respawn = block.get("respawn", [])
        clear = block.get("clear", [])
        vip = block.get("vip", [])
        exceptional = block.get("exceptional", [])
        text = block.get("text", "")
        countdown = int(block.get("countdown", 0))

        lines.extend(
            [
                ";",
                f"; [{index:02d} block]",
                "; info 01 : block rect (l,t,r,b)",
                "; left\ttop\tright\tbottom",
                "\t".join(str(value) for value in rect),
                "; info 02 : valid spawn array",
                format_list(enemies),
                "; info 03 : rebirth spawn array",
                format_list(respawn),
                "; info 04 : trigger spawn array",
                format_list(clear),
                "; info 05 : VIP",
                format_list(vip),
                "; info 06 : exceptional spawn",
                format_list(exceptional),
                "; info 07 : block text",
                str(text),
                "; info 08 : countdown",
                str(countdown),
            ]
        )

    return "\r\n".join(lines) + "\r\n"


def main() -> None:
    args = parse_args()

    header, entries, names, offsets, extra = read_header(args.base_bin)
    data = args.base_bin.read_bytes()

    original_payloads: dict[str, bytes] = {}
    for index, name in enumerate(names):
        start = offsets[index]
        end = offsets[index + 1] if index + 1 < len(offsets) else len(data)
        original_payloads[name] = data[start:end]

    packed_entries: list[bytes] = []
    for name in names:
        if name.lower().endswith(".json"):
            json_name = Path(name).name
        elif name.lower().endswith(".dun"):
            json_name = Path(name).stem + ".json"
        else:
            json_name = f"{name}.json"
        json_path = args.input_dir / json_name

        if json_path.exists():
            payload = load_json(json_path)
        else:
            packed_entries.append(original_payloads[name])
            continue

        text = render_dun(payload)
        encoded = text.encode("cp949")
        encrypted = encrypt(encoded)
        header_bytes = struct.pack("<2I", 1, len(encrypted))
        packed_entries.append(header_bytes + encrypted)

    data_start = 16 + len(entries) * 260 + len(names) * 4 + len(extra)
    new_offsets: list[int] = []
    cursor = data_start
    for entry in packed_entries:
        new_offsets.append(cursor)
        cursor += len(entry)

    args.output_bin.parent.mkdir(parents=True, exist_ok=True)
    with args.output_bin.open("wb") as handle:
        handle.write(header)
        for entry in entries:
            handle.write(entry)
        handle.write(struct.pack(f"<{len(new_offsets)}I", *new_offsets))
        handle.write(extra)
        for entry in packed_entries:
            handle.write(entry)

    print(f"Packed {len(packed_entries)} files to {args.output_bin}")


if __name__ == "__main__":
    main()