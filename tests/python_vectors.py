
import json, os, sys
sys.path.insert(0, os.path.join(os.path.dirname(__file__), "..", "packages", "python"))
from base33 import encode, decode
vectors=json.load(open(os.path.join(os.path.dirname(__file__),"vectors.json")))
for v in vectors:
    data=bytes.fromhex(v["hex"]) if v["hex"] else b""
    s=encode(data)
    assert s==v["base33"]
    assert decode(s)==data
print("Python OK")
