
import fs from 'fs'; import path from 'path'; import url from 'url';
import { encode, decode } from '../packages/js/base33.js';
const __dirname=path.dirname(url.fileURLToPath(import.meta.url));
const vs=JSON.parse(fs.readFileSync(path.join(__dirname,'vectors.json'),'utf8'));
for(const v of vs){
  const data = v.hex ? Buffer.from(v.hex,'hex') : Buffer.alloc(0);
  const s = encode(data);
  if(s!==v.base33){ console.error('enc mismatch', v, s); process.exit(1); }
  const out = decode(s);
  if(Buffer.compare(out,data)!==0){ console.error('dec mismatch', v); process.exit(1); }
}
console.log("Node OK");
