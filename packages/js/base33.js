export const ALPHABET="0123456789ABCDEFGHJKMNPQRSTUVWXYZ";
const CANON=new Map(Object.entries({"i": "1", "I": "1", "l": "1", "L": "1", "o": "0", "O": "0"}));

function canonicalize(str){
  let out="";
  for(const ch of str){
    let c=CANON.get(ch) ?? ch;
    if(c>='a' && c<='z') c=String.fromCharCode(c.charCodeAt(0)-32);
    out+=c;
  }
  return out;
}
export function encode(input){
  let data = typeof input==="string" ? Buffer.from(input,"utf8") : Buffer.from(input);
  if(!data.length) return "";
  let zeros=0; for(const b of data){ if(b===0) zeros++; else break; }
  const payload=data.slice(zeros);
  if(!payload.length) return "0".repeat(zeros);
  let num=0n; for(const b of payload) num=(num<<8n)+BigInt(b);
  let out="";
  while(num>0n){ const rem=Number(num%33n); num=num/33n; out=ALPHABET[rem]+out; }
  return "0".repeat(zeros)+out;
}
export function decode(str){
  if(!str) return Buffer.alloc(0);
  const s2=canonicalize(str);
  let zeros=0; for(const ch of s2){ if(ch==='0') zeros++; else break; }
  const core=s2.slice(zeros);
  let num=0n;
  for(const ch of core){ const idx=ALPHABET.indexOf(ch); if(idx<0) throw new Error("Invalid Base33 character: "+ch); num=num*33n+BigInt(idx); }
  let hex=num.toString(16); if(hex.length%2) hex="0"+hex;
  const coreBytes=hex?Buffer.from(hex,"hex"):Buffer.alloc(0);
  return Buffer.concat([Buffer.alloc(zeros,0), coreBytes]);
}
