#!/usr/bin/env python3
"""Extract embedded files and JSON blobs from a dungeon .bin archive."""

import argparse
import hashlib
import json
from pathlib import Path
from typing import Iterable, Optional, Tuple


def iter_start_positions(data: bytes, needle: bytes) -> Iterable[int]:
    start = 0
    while True:
        idx = data.find(needle, start)
        if idx == -1:
            return
        yield idx
        start = idx + 1


def extract_json_from_text(text: str) -> Optional[str]:
    if not text or text[0] not in "[{":
        return None

    stack = [text[0]]
    in_string = False
    escape = False

    for i in range(1, len(text)):
        ch = text[i]
        if in_string:
            if escape:
                escape = False
                continue
            if ch == "\\":
                escape = True
                continue
            if ch == '"':
                in_string = False
            continue

        if ch == '"':
            in_string = True
            continue
        if ch in "[{":
            stack.append(ch)
            continue
        if ch in "]}":
            if not stack:
                return None
            opener = stack.pop()
            if opener == "[" and ch != "]":
                return None
            if opener == "{" and ch != "}":
                return None
            if not stack:
                return text[: i + 1]

    return None


def try_decode_and_extract(text: str) -> Optional[str]:
    json_text = extract_json_from_text(text)
    if not json_text:
        return None

    try:
        json.loads(json_text)
    except json.JSONDecodeError:
        return None
    return json_text


def iter_json_candidates(text: str) -> Iterable[str]:
    start = 0
    while start < len(text):
        next_obj = text.find("{", start)
        next_arr = text.find("[", start)
        if next_obj == -1 and next_arr == -1:
            return
        if next_obj == -1:
            idx = next_arr
        elif next_arr == -1:
            idx = next_obj
        else:
            idx = min(next_obj, next_arr)
        candidate = try_decode_and_extract(text[idx:])
        if candidate:
            yield candidate
            start = idx + len(candidate)
        else:
            start = idx + 1


def collect_json_blobs(data: bytes) -> Iterable[Tuple[str, str]]:
    seen = set()

    for encoding, text in (
        ("utf-8", data.decode("utf-8", errors="ignore")),
        ("utf-16-le", data.decode("utf-16-le", errors="ignore")),
        ("utf-16-le-offset1", data[1:].decode("utf-16-le", errors="ignore")),
        ("utf-16-be", data.decode("utf-16-be", errors="ignore")),
        ("utf-16-be-offset1", data[1:].decode("utf-16-be", errors="ignore")),
        ("cp949", data.decode("cp949", errors="ignore")),
    ):
        for json_text in iter_json_candidates(text):
            digest = hashlib.sha256(json_text.encode("utf-8")).hexdigest()
            if digest not in seen:
                seen.add(digest)
                yield (encoding, json_text)


def xor_decode(data: bytes, key: int = 0xFF) -> bytes:
    return bytes(b ^ key for b in data)


def score_text(text: str) -> float:
    if not text:
        return 0.0
    printable = sum(1 for ch in text if ch.isprintable() or ch in "\r\n\t")
    return printable / len(text)


def decode_text_best_effort(data: bytes) -> Tuple[str, str]:
    candidates = []
    for encoding in ("cp949", "utf-8", "utf-16-le", "utf-16-be"):
        decoded = data.decode(encoding, errors="ignore")
        candidates.append((score_text(decoded), encoding, decoded))
    candidates.sort(reverse=True)
    _, encoding, decoded = candidates[0]
    return encoding, decoded


def detect_compression_signatures(data: bytes) -> list[str]:
    signatures = {
        b"\x1f\x8b": "gzip",
        b"\x78\x01": "zlib",
        b"\x78\x9c": "zlib",
        b"\x78\xda": "zlib",
        b"\x04\x22\x4d\x18": "lz4",
        b"\x28\xb5\x2f\xfd": "zstd",
        b"PK\x03\x04": "zip",
    }
    found = []
    for magic, name in signatures.items():
        if data.startswith(magic):
            found.append(f"{name} (header)")
    for magic, name in signatures.items():
        if magic in data and f"{name} (header)" not in found:
            found.append(f"{name} (embedded)")
    return found


def is_xor_obfuscated(data: bytes) -> Tuple[bool, float, float]:
    raw_text = data.decode("cp949", errors="ignore")
    raw_score = score_text(raw_text)
    xor_text = xor_decode(data).decode("cp949", errors="ignore")
    xor_score = score_text(xor_text)
    return xor_score > raw_score + 0.2, raw_score, xor_score


def parse_archive(data: bytes) -> Tuple[list[str], list[Tuple[int, int]], dict]:
    if len(data) < 16:
        raise ValueError("Input too small to be a valid archive")

    header_count = int.from_bytes(data[12:16], "little")
    if header_count <= 0:
        raise ValueError("Invalid file count in header")

    import re

    name_matches = list(re.finditer(b"[A-Za-z0-9_]+\\.dun\x00", data))
    if not name_matches:
        raise ValueError("Could not locate any .dun entries in archive")

    if len(name_matches) < header_count:
        header_count = len(name_matches)

    name_positions = [match.start() for match in name_matches[:header_count]]
    entry_size = min(
        name_positions[i + 1] - name_positions[i]
        for i in range(len(name_positions) - 1)
    )
    entry_offset = name_positions[0]
    meta_offset = entry_offset + entry_size * header_count

    if meta_offset + header_count * 4 > len(data):
        raise ValueError("Offset table exceeds archive size")

    names = []
    for i in range(header_count):
        entry = data[entry_offset + i * entry_size : entry_offset + (i + 1) * entry_size]
        name = entry.split(b"\x00", 1)[0].decode("ascii", errors="replace")
        names.append(name)

    offsets = [
        int.from_bytes(
            data[meta_offset + i * 4 : meta_offset + (i + 1) * 4], "little"
        )
        for i in range(header_count)
    ]

    if any(offset < 0 or offset >= len(data) for offset in offsets):
        raise ValueError("Found invalid file offsets in archive")

    if offsets != sorted(offsets):
        raise ValueError("Offsets are not sorted; archive format not recognized")

    sizes = [
        offsets[i + 1] - offsets[i] for i in range(header_count - 1)
    ] + [len(data) - offsets[-1]]

    metadata = {
        "header_count": header_count,
        "entry_size": entry_size,
        "entry_offset": entry_offset,
        "offset_table_offset": meta_offset,
    }

    return names, list(zip(offsets, sizes)), metadata


def analyze_archive(
    data: bytes, names: list[str], entries: list[Tuple[int, int]], metadata: dict
) -> dict:
    analysis = {
        "file_size": len(data),
        "entry_count": len(names),
        "archive_metadata": metadata,
        "entries": [],
    }
    for name, (offset, size) in zip(names, entries):
        blob = data[offset : offset + size]
        xor_flag, raw_score, xor_score = is_xor_obfuscated(blob)
        encoding, _ = decode_text_best_effort(xor_decode(blob) if xor_flag else blob)
        analysis["entries"].append(
            {
                "name": name,
                "offset": offset,
                "size": size,
                "compression_signatures": detect_compression_signatures(blob),
                "xor_0xff_likely": xor_flag,
                "raw_text_score": round(raw_score, 3),
                "xor_text_score": round(xor_score, 3),
                "likely_encoding": encoding,
            }
        )
    analysis["compression_signatures"] = detect_compression_signatures(data)
    return analysis


def main() -> None:
    parser = argparse.ArgumentParser(
        description=(
            "Extract files from a dungeon .bin archive and locate JSON blobs. "
            "Attempts UTF-8/UTF-16 decoding for JSON payloads."
        )
    )
    parser.add_argument("input", nargs="?", default="dungeon.bin")
    parser.add_argument("-o", "--output", default="extracted_files")
    parser.add_argument("--json-only", action="store_true", help="Only write JSON blobs")
    parser.add_argument(
        "--decrypt",
        action="store_true",
        help="Also decode XOR-0xFF encrypted payloads into text files",
    )
    parser.add_argument(
        "--analyze",
        action="store_true",
        help="Write analysis report (analysis_report.json)",
    )
    args = parser.parse_args()

    input_path = Path(args.input)
    output_dir = Path(args.output)
    output_dir.mkdir(parents=True, exist_ok=True)

    data = input_path.read_bytes()

    names, entries, metadata = parse_archive(data)
    if args.analyze:
        analysis = analyze_archive(data, names, entries, metadata)
        report_path = output_dir / "analysis_report.json"
        report_path.write_text(json.dumps(analysis, indent=2), encoding="utf-8")

    json_count = 0
    file_count = 0
    decoded_count = 0

    decoded_dir = output_dir / "decoded"
    if args.decrypt and not args.json_only:
        decoded_dir.mkdir(parents=True, exist_ok=True)
    for name, (offset, size) in zip(names, entries):
        blob = data[offset : offset + size]

        if not args.json_only:
            file_count += 1
            out_path = output_dir / name
            out_path.write_bytes(blob)

        scan_targets = [("raw", blob)]
        xor_flag, _, _ = is_xor_obfuscated(blob)

        if args.decrypt or xor_flag:
            decrypted = xor_decode(blob)
            scan_targets.append(("xor-ff", decrypted))

            if args.decrypt and not args.json_only:
                encoding, decoded_text = decode_text_best_effort(decrypted)
                decoded_path = decoded_dir / f"{Path(name).stem}.{encoding}.txt"
                decoded_path.write_text(decoded_text, encoding="utf-8")
                decoded_count += 1

        for label, payload in scan_targets:
            for encoding, json_text in collect_json_blobs(payload):
                json_count += 1
                output_encoding = encoding
                if encoding.endswith("offset1"):
                    output_encoding = encoding.replace("-offset1", "")
                out_path = (
                    output_dir
                    / f"{Path(name).stem}_{label}_{json_count:03d}_{encoding}.json"
                )
                out_path.write_text(json_text, encoding=output_encoding)

    summary = (
        f"Extracted {file_count} file(s), {decoded_count} decoded file(s), "
        f"and {json_count} JSON blob(s) to {output_dir}"
    )
    print(summary)


if __name__ == "__main__":
    main()