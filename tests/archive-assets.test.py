from pathlib import Path

from PIL import Image


ROOT = Path(__file__).resolve().parents[1]
ASSETS = ROOT / "wordpress/themes/flatsome-child/assets/archive-products"


def content_bbox(image):
    rgb = image.convert("RGB")
    pixels = rgb.load()
    xs = []
    ys = []
    for y in range(rgb.height):
        for x in range(rgb.width):
            r, g, b = pixels[x, y]
            if min(r, g, b) < 238 or max(r, g, b) - min(r, g, b) > 12:
                xs.append(x)
                ys.append(y)
    return min(xs), min(ys), max(xs) + 1, max(ys) + 1


originals = sorted(ASSETS.glob("pti-*-1.*"))
edits = sorted(ASSETS.glob("pti-*-2.png"))
assert len(originals) == 17
assert len(edits) == 17

for path in originals:
    with Image.open(path) as image:
        left, top, right, bottom = content_bbox(image)
        assert left / image.width < 0.08, f"{path.name} keeps a large left white border"
        assert top / image.height < 0.08, f"{path.name} keeps a large top white border"
        assert (image.width - right) / image.width < 0.08, f"{path.name} keeps a large right white border"
        assert (image.height - bottom) / image.height < 0.08, f"{path.name} keeps a large bottom white border"

for path in edits:
    with Image.open(path).convert("RGBA") as image:
        alpha = image.getchannel("A")
        assert alpha.getextrema()[0] == 0, f"{path.name} has no transparent background"
        assert alpha.getpixel((0, 0)) == 0, f"{path.name} corner is not transparent"

print("archive product image contract passed")
