<?php
namespace Base33;
class Base33{private const ALPHABET="0123456789ABCDEFGHJKMNPQRSTUVWXYZ"; private static $CANON={"i": "1", "I": "1", "l": "1", "L": "1", "o": "0", "O": "0"};
public static function encode(string $data): string{
  $len=strlen($data); if($len===0) return "";
  $zeros=0; for($i=0;$i<$len;$i++){ if(ord($data[$i])===0) $zeros++; else break; }
  $payload=substr($data,$zeros); if($payload==="") return str_repeat("0",$zeros);
  $num=gmp_init(0); for($i=0;$i<strlen($payload);$i++){ $num=gmp_add(gmp_mul($num,256), ord($payload[$i])); }
  $out=""; while(gmp_cmp($num,0)>0){ $rem=gmp_intval(gmp_mod($num,33)); $num=gmp_div_q($num,33); $out=self::ALPHABET[$rem].$out; }
  return str_repeat("0",$zeros).$out;
}
private static function canon(string $s): string{
  $o=""; for($i=0;$i<strlen($s);$i++){ $ch=$s[$i]; if(isset(self::$CANON[$ch])) $ch=self::$CANON[$ch]; $ord=ord($ch); if($ord>=97 && $ord<=122) $ch=chr($ord-32); $o.=$ch; } return $o;
}
public static function decode(string $s): string{
  if($s==="") return "";
  $s2=self::canon($s); $zeros=0; for($i=0;$i<strlen($s2);$i++){ if($s2[$i]==='0') $zeros++; else break; }
  $core=substr($s2,$zeros); $num=gmp_init(0);
  for($i=0;$i<strlen($core);$i++){ $ch=$core[$i]; $idx=strpos(self::ALPHABET,$ch); if($idx===false) throw new \InvalidArgumentException("Invalid Base33 character: ".$ch); $num=gmp_mul($num,33); $num=gmp_add($num,$idx); }
  $hex=gmp_strval($num,16); if($hex==="0") $hex=""; if(strlen($hex)%2) $hex="0".$hex; $coreBytes=($hex!=="")?hex2bin($hex):"";
  return str_repeat("\x00",$zeros).$coreBytes;
}}