#!/usr/bin/env python3

from pathlib import Path

from PIL import Image


ROOT = Path(__file__).resolve().parents[1]
ASSETS = ROOT / "wordpress/themes/flatsome-child/assets/archive-products"


def artwork_bbox(image):
    rgb = image.convert("RGB")
    pixels = rgb.load()
    row_counts = [0] * rgb.height
    column_counts = [0] * rgb.width
    for y in range(rgb.height):
        for x in range(rgb.width):
            r, g, b = pixels[x, y]
            if min(r, g, b) < 225 or max(r, g, b) - min(r, g, b) > 20:
                row_counts[y] += 1
                column_counts[x] += 1
    row_floor = max(4, round(rgb.width * 0.003))
    column_floor = max(4, round(rgb.height * 0.003))
    rows = [index for index, count in enumerate(row_counts) if count >= row_floor]
    columns = [index for index, count in enumerate(column_counts) if count >= column_floor]
    if not rows or not columns:
        return 0, 0, image.width, image.height
    return min(columns), min(rows), max(columns) + 1, max(rows) + 1


def padded_crop(image, box, ratio=0.025):
    left, top, right, bottom = box
    pad = round(max(right - left, bottom - top) * ratio)
    return image.crop(
        (
            max(0, left - pad),
            max(0, top - pad),
            min(image.width, right + pad),
            min(image.height, bottom + pad),
        )
    )


def prepare_original(path):
    with Image.open(path) as source:
        image = source.convert("RGBA" if path.suffix.lower() == ".png" else "RGB")
        image = padded_crop(image, artwork_bbox(image))
        if path.suffix.lower() == ".jpg":
            image.save(path, quality=91, optimize=True, progressive=True)
        else:
            image.save(path, optimize=True)


def prepare_edit(path):
    with Image.open(path) as source:
        image = source.convert("RGBA")
    pixels = image.load()
    is_dream = path.name == "pti-013-2.png"

    for y in range(image.height):
        for x in range(image.width):
            r, g, b, _ = pixels[x, y]
            spread = max(r, g, b) - min(r, g, b)

            if spread < 20 and min(r, g, b) > 210:
                alpha = max(0, min(255, round((255 - min(r, g, b)) * 5.67)))
                pixels[x, y] = (r, g, b, alpha)
                continue

            if is_dream:
                if x < image.width * 0.46 and y < image.height * 0.13:
                    pixels[x, y] = (r, g, b, 0)
            elif spread < 20 and max(r, g, b) < 210:
                pixels[x, y] = (r, g, b, 0)

    alpha_box = image.getchannel("A").getbbox()
    if alpha_box:
        image = padded_crop(image, alpha_box, ratio=0.035)
    image.save(path, optimize=True)


for asset in sorted(ASSETS.glob("pti-*-1.*")):
    prepare_original(asset)

for asset in sorted(ASSETS.glob("pti-*-2.png")):
    prepare_edit(asset)

print("prepared 17 original artworks and 17 transparent edits")
