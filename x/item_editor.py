#!/usr/bin/env python
"""Simple editor for item-2.bin records."""
from __future__ import annotations

import argparse
import json
import os
import struct
from dataclasses import dataclass
from pathlib import Path
from typing import Iterable, Tuple

RECORD_SIZE = 236
HEADER_SIZE = 8
NAME_SIZE = 32
STATS_OFFSET = 32
STATS_SIZE = 99
DESCRIPTION_OFFSET = 131
DESCRIPTION_SIZE = 101
TAIL_OFFSET = 232
TAIL_SIZE = 4


@dataclass
class ItemRecord:
    index: int
    name: str
    description: str
    stats: bytes
    tail: bytes

    def to_dict(self) -> dict:
        return {
            "index": self.index,
            "name": self.name,
            "description": self.description,
            "stats_hex": self.stats.hex(),
            "tail_hex": self.tail.hex(),
        }


def _decode_text(raw: bytes) -> str:
    return raw.split(b"\x00", 1)[0].decode("ascii", errors="replace")


def _encode_text(text: str, size: int) -> bytes:
    encoded = text.encode("ascii", errors="replace")
    if len(encoded) > size:
        raise ValueError(f"Text too long for field (max {size} bytes).")
    return encoded.ljust(size, b"\x00")


def _parse_hex(value: str, size: int) -> bytes:
    cleaned = value.replace(" ", "").replace("0x", "")
    raw = bytes.fromhex(cleaned)
    if len(raw) != size:
        raise ValueError(f"Expected {size} bytes, got {len(raw)} bytes.")
    return raw


def _read_records(data: bytes) -> Tuple[int, int, Iterable[ItemRecord]]:
    if len(data) < HEADER_SIZE:
        raise ValueError("File too small to contain header.")
    count, unknown = struct.unpack_from("<II", data, 0)
    available = (len(data) - HEADER_SIZE) // RECORD_SIZE
    if count > available:
        count = available
    records = []
    for i in range(count):
        start = HEADER_SIZE + i * RECORD_SIZE
        rec = data[start : start + RECORD_SIZE]
        name = _decode_text(rec[:NAME_SIZE])
        stats = rec[STATS_OFFSET : STATS_OFFSET + STATS_SIZE]
        description = _decode_text(
            rec[DESCRIPTION_OFFSET : DESCRIPTION_OFFSET + DESCRIPTION_SIZE]
        )
        tail = rec[TAIL_OFFSET : TAIL_OFFSET + TAIL_SIZE]
        records.append(ItemRecord(i, name, description, stats, tail))
    return count, unknown, records


def _write_record(data: bytearray, record: ItemRecord) -> None:
    start = HEADER_SIZE + record.index * RECORD_SIZE
    data[start : start + NAME_SIZE] = _encode_text(record.name, NAME_SIZE)
    data[start + STATS_OFFSET : start + STATS_OFFSET + STATS_SIZE] = record.stats
    data[
        start + DESCRIPTION_OFFSET : start + DESCRIPTION_OFFSET + DESCRIPTION_SIZE
    ] = _encode_text(record.description, DESCRIPTION_SIZE)
    data[start + TAIL_OFFSET : start + TAIL_OFFSET + TAIL_SIZE] = record.tail


def load_file(path: Path) -> bytes:
    return path.read_bytes()


def save_file(path: Path, data: bytes) -> None:
    tmp_path = path.with_suffix(path.suffix + ".tmp")
    tmp_path.write_bytes(data)
    tmp_path.replace(path)


def cmd_list(args: argparse.Namespace) -> None:
    data = load_file(args.file)
    count, _, records = _read_records(data)
    print(f"Records: {count}")
    for rec in records:
        print(f"{rec.index:4d}  {rec.name}")


def cmd_show(args: argparse.Namespace) -> None:
    data = load_file(args.file)
    count, unknown, records = _read_records(data)
    if args.index < 0 or args.index >= count:
        raise SystemExit(f"Index out of range (0-{count - 1}).")
    record = next(rec for rec in records if rec.index == args.index)
    payload = record.to_dict()
    payload["header_unknown"] = unknown
    print(json.dumps(payload, indent=2, ensure_ascii=False))


def cmd_set(args: argparse.Namespace) -> None:
    data = bytearray(load_file(args.file))
    count, _, records = _read_records(data)
    if args.index < 0 or args.index >= count:
        raise SystemExit(f"Index out of range (0-{count - 1}).")
    record = next(rec for rec in records if rec.index == args.index)
    if args.name is not None:
        record.name = args.name
    if args.description is not None:
        record.description = args.description
    if args.stats_hex is not None:
        record.stats = _parse_hex(args.stats_hex, STATS_SIZE)
    if args.tail_hex is not None:
        record.tail = _parse_hex(args.tail_hex, TAIL_SIZE)
    _write_record(data, record)
    output = args.output or args.file
    save_file(Path(output), data)
    print(f"Saved record {record.index} to {output}.")


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Item editor for item-2.bin")
    parser.add_argument(
        "--file",
        type=Path,
        default=Path("item-2.bin"),
        help="Path to the item binary file.",
    )
    subparsers = parser.add_subparsers(dest="command", required=True)

    list_parser = subparsers.add_parser("list", help="List item names")
    list_parser.set_defaults(func=cmd_list)

    show_parser = subparsers.add_parser("show", help="Show item details")
    show_parser.add_argument("index", type=int, help="Item index")
    show_parser.set_defaults(func=cmd_show)

    set_parser = subparsers.add_parser("set", help="Update an item")
    set_parser.add_argument("index", type=int, help="Item index")
    set_parser.add_argument("--name", help="Update item name")
    set_parser.add_argument("--description", help="Update item description")
    set_parser.add_argument(
        "--stats-hex", help=f"Set {STATS_SIZE}-byte stats as hex string"
    )
    set_parser.add_argument(
        "--tail-hex", help=f"Set {TAIL_SIZE}-byte tail as hex string"
    )
    set_parser.add_argument(
        "--output",
        type=Path,
        help="Optional output path (defaults to overwriting the input).",
    )
    set_parser.set_defaults(func=cmd_set)

    return parser


def main() -> None:
    parser = build_parser()
    args = parser.parse_args()
    args.func(args)


if __name__ == "__main__":
    main()