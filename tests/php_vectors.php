
<?php
require __DIR__.'/../packages/php/Base33.php';
use Base33\Base33;
$vs=json_decode(file_get_contents(__DIR__.'/vectors.json'),true);
foreach($vs as $v){
  $data = ($v['hex']!=="") ? hex2bin($v['hex']) : "";
  $s = Base33::encode($data);
  if($s !== $v['base33']){ var_dump($v,$s); exit(1); }
  $out = Base33::decode($s);
  if($out !== $data){ echo "Roundtrip mismatch\n"; var_dump($v); exit(1); }
}
echo "PHP OK\n";
