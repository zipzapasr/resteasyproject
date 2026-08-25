#!/usr/bin/env python3
"""Append restored service-page CSS to style.css (lost on revert)."""
from pathlib import Path

base = Path(__file__).resolve().parents[1] / "assets" / "css"
style_path = base / "style.css"
style = style_path.read_text(encoding="utf-8")

if ".hc-intro {" in style:
    raise SystemExit("style.css already contains .hc-intro — aborting")

hc = (base / "_hc-restore.css").read_text(encoding="utf-8")
idx_why = hc.find(".hc-why {")
idx_process = hc.find(".hc-process {")
if idx_why < 0 or idx_process < 0:
    raise SystemExit("Unexpected _hc-restore.css structure")

head = hc[:idx_why].rstrip()
process_faq = hc[idx_process:].rstrip()

included = (base / "_part_29.css").read_text(encoding="utf-8")
inc_idx = included.find(".hc-included {")
included = included[inc_idx:].rstrip()

why = (base / "_part_35.css").read_text(encoding="utf-8").rstrip()

extra_bits = [
    """
.hc-offer__cta {
  margin-top: 22px;
}

.hc-offer__cta .thm-btn {
  min-width: 140px;
}

.hc-offers__mobile-nav,
.hc-included__mobile-nav {
  display: none;
}

.hc-steps--3 {
  grid-template-columns: repeat(3, 1fr);
}
""".strip()
]

for name in ("_bed_80.css", "_bed_114.css", "_bed_102.css", "_bed_115.css", "_nearby.css"):
    path = base / name
    if not path.exists():
        continue
    txt = path.read_text(encoding="utf-8").rstrip()
    if txt.endswith(".hc-prep {"):
        txt = txt[: txt.rfind(".hc-prep {")].rstrip()
    if txt:
        extra_bits.append(txt)

banner = """/***
=============================================
Service Pages (house / vacate / shared)
=============================================
***/"""

block = "\n\n".join([banner, head, included, why, process_faq, *extra_bits]) + "\n"
out = style.rstrip() + "\n\n" + block
style_path.write_text(out, encoding="utf-8")
print(f"OK appended {len(block)} bytes; total {len(out)}")
print(f"hc-intro={out.count('.hc-intro {')} why-item={out.count('.hc-why-item {')} included={out.count('.hc-included {')}")
