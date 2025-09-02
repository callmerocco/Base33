ALPHABET="0123456789ABCDEFGHJKMNPQRSTUVWXYZ"
_CANON={'i': '1', 'I': '1', 'l': '1', 'L': '1', 'o': '0', 'O': '0'}

def encode(data: bytes) -> str:
    if not data: return ""
    zeros=0
    for b in data:
        if b==0: zeros+=1
        else: break
    payload=data[zeros:]
    if not payload: return "0"*zeros
    num=int.from_bytes(payload,"big")
    out=[]
    while num:
        num,rem=divmod(num,33)
        out.append(ALPHABET[rem])
    return "0"*zeros + "".join(reversed(out))

def decode(s: str) -> bytes:
    if s=="": return b""
    buf=[]
    for ch in s:
        ch=_CANON.get(ch,ch)
        if "a"<=ch<="z": ch=chr(ord(ch)-32)
        buf.append(ch)
    s2="".join(buf)
    zeros=0
    for ch in s2:
        if ch=="0": zeros+=1
        else: break
    core=s2[zeros:]
    n=0
    for ch in core:
        idx=ALPHABET.find(ch)
        if idx<0: raise ValueError(f"Invalid Base33 character: {ch!r}")
        n=n*33+idx
    core_bytes= n.to_bytes((n.bit_length()+7)//8,"big") if n else b""
    return b"\x00"*zeros + core_bytes
