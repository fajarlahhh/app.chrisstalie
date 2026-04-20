#!/usr/bin/env python3
"""
Insert HTML id on input/select/textarea from wire:model value (dots -> hyphens).
Skips tags that already have id=. Resolves duplicate static ids in the same file.
Skips matches inside @php ... @endphp blocks.
"""

from __future__ import annotations

import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
RESOURCES = ROOT / "resources"

WIRE_RE = re.compile(
    r"""wire:model(?:\.(?:live|lazy|defer|blur))?\s*=\s*(["'])(.*?)\1""",
    re.IGNORECASE | re.DOTALL,
)
TAG_START_RE = re.compile(r"<\s*(input|select|textarea)\b", re.IGNORECASE)


def php_block_spans(text: str) -> list[tuple[int, int]]:
    spans: list[tuple[int, int]] = []
    pos = 0
    while True:
        m = re.search(r"@php\b", text[pos:], re.IGNORECASE)
        if not m:
            break
        start = pos + m.start()
        end_marker = re.search(r"@endphp\b", text[start + 4 :], re.IGNORECASE)
        if not end_marker:
            break
        end = start + 4 + end_marker.end() + end_marker.start()
        spans.append((start, end))
        pos = end
    return spans


def in_any_span(idx: int, spans: list[tuple[int, int]]) -> bool:
    return any(a <= idx < b for a, b in spans)


def find_tag_end(s: str, pos: int) -> int:
    """First '>' not in quotes and not inside (...), e.g. @if ($a > $b)."""
    i = pos
    quote: str | None = None
    depth = 0
    n = len(s)
    while i < n:
        c = s[i]
        if quote == '"':
            if c == "\\" and i + 1 < n:
                i += 2
                continue
            if c == '"':
                quote = None
            i += 1
            continue
        if quote == "'":
            if c == "\\" and i + 1 < n:
                i += 2
                continue
            if c == "'":
                quote = None
            i += 1
            continue
        if quote is None:
            if c == '"':
                quote = '"'
                i += 1
                continue
            if c == "'":
                quote = "'"
                i += 1
                continue
            if c == "(":
                depth += 1
                i += 1
                continue
            if c == ")" and depth > 0:
                depth -= 1
                i += 1
                continue
            if c == ">" and depth == 0:
                return i
        i += 1
    return -1


def model_to_id_fragment(model: str) -> str:
    """Fragment for id= (may contain Blade). Dots -> hyphens around Blade."""
    s = model.strip()
    s = re.sub(r"\.\s*\{\{", "-{{", s)
    s = re.sub(r"\}\}\s*\.", "}}-", s)
    s = s.replace(".", "-")
    return s


def has_id_attr(tag: str) -> bool:
    return re.search(r"\bid\s*=", tag, re.IGNORECASE) is not None


def insert_id_after_tag_name(tag: str, id_attr: str) -> str:
    m = TAG_START_RE.match(tag)
    if not m:
        return tag
    name = m.group(1)
    rest = tag[m.end() :]
    return f"<{name} {id_attr}{rest}"


def process_file(path: Path) -> bool:
    text = path.read_text(encoding="utf-8")
    spans = php_block_spans(text)
    if "wire:model" not in text.lower():
        return False

    replacements: list[tuple[int, int, str]] = []
    id_use_count: dict[str, int] = {}

    pos = 0
    while True:
        m = TAG_START_RE.search(text, pos)
        if not m:
            break
        if in_any_span(m.start(), spans):
            pos = m.end()
            continue
        end = find_tag_end(text, m.end())
        if end == -1:
            pos = m.end()
            continue
        tag = text[m.start() : end + 1]
        pos = end + 1

        if "wire:model" not in tag.lower():
            continue
        if has_id_attr(tag):
            continue
        wm = WIRE_RE.search(tag)
        if not wm:
            continue
        model = wm.group(2)
        id_frag = model_to_id_fragment(model)
        if not id_frag:
            continue

        # Duplicate static ids in one file: append -2, -3 (Blade ids skipped)
        if "{{" not in id_frag and "}}" not in id_frag:
            id_use_count[id_frag] = id_use_count.get(id_frag, 0) + 1
            c = id_use_count[id_frag]
            final_id = id_frag if c == 1 else f"{id_frag}-{c}"
        else:
            final_id = id_frag

        id_attr = f'id="{final_id}"'
        new_tag = insert_id_after_tag_name(tag, id_attr)
        replacements.append((m.start(), end + 1, new_tag))

    if not replacements:
        return False

    # Apply from end to start
    out = text
    for start, end, new_tag in sorted(replacements, key=lambda x: -x[0]):
        out = out[:start] + new_tag + out[end:]

    if out != text:
        path.write_text(out, encoding="utf-8")
        return True
    return False


def main() -> int:
    changed = 0
    for blade in sorted(RESOURCES.rglob("*.blade.php")):
        try:
            if process_file(blade):
                changed += 1
                print(blade.relative_to(ROOT))
        except Exception as e:
            print(f"ERROR {blade}: {e}", file=sys.stderr)
            return 1
    print(f"Updated {changed} files.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
