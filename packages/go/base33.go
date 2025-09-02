package base33

import ("math/big"
"strings"
"errors"
)
const ALPHABET="0123456789ABCDEFGHJKMNPQRSTUVWXYZ"
var canonMap=map[rune]rune{'i':'1','I':'1','l':'1','L':'1','o':'0','O':'0'}

func canonicalize(s string) string{
  b := strings.Builder{}
  for _,ch := range s{
    if m,ok := canonMap[ch]; ok { ch=m }
    if ch>='a' && ch<='z' { ch=ch-32 }
    b.WriteRune(ch)
  }
  return b.String()
}
func Encode(data []byte) string{
  if len(data)==0 { return "" }
  zeros:=0; for _,v := range data { if v==0 { zeros++ } else { break } }
  payload := data[zeros:]
  if len(payload)==0 { return strings.Repeat("0", zeros) }
  num := big.NewInt(0)
  for _,v := range payload { num.Mul(num,big.NewInt(256)); num.Add(num,big.NewInt(int64(v))) }
  base := big.NewInt(33); rem := new(big.Int)
  out := []byte{}
  for num.Cmp(big.NewInt(0))>0 {
    num, rem = new(big.Int).DivMod(num, base, rem)
    out = append(out, ALPHABET[rem.Int64()])
  }
  for i,j := 0,len(out)-1; i<j; i,j=i+1,j-1 { out[i],out[j]=out[j],out[i] }
  return strings.Repeat("0",zeros) + string(out)
}
func Decode(s string) ([]byte, error){
  if s=="" { return []byte{}, nil }
  s2 := canonicalize(s)
  zeros:=0; for _,ch := range s2 { if ch=='0' { zeros++ } else { break } }
  core := s2[zeros:]
  num := big.NewInt(0)
  for _,ch := range core {
    idx := strings.IndexRune(ALPHABET, ch)
    if idx<0 { return nil, errors.New("invalid Base33 character") }
    num.Mul(num,big.NewInt(33)); num.Add(num,big.NewInt(int64(idx)))
  }
  coreBytes := num.Bytes()
  result := make([]byte, zeros+len(coreBytes))
  copy(result[zeros:], coreBytes)
  return result, nil
}
