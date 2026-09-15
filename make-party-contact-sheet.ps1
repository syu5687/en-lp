Add-Type -AssemblyName System.Drawing
$files = Get-ChildItem 'D:\_LUM\model-sns-pipeline\assets\previews' -Filter 'gb_iizuka_party_*.png' | Sort-Object Name
$thumbW=448; $thumbH=256; $cols=3; $rows=[Math]::Ceiling($files.Count/$cols)
$sheet=New-Object System.Drawing.Bitmap ($thumbW*$cols),($thumbH*$rows)
$g=[System.Drawing.Graphics]::FromImage($sheet); $g.Clear([System.Drawing.Color]::White)
for($i=0;$i -lt $files.Count;$i++) { $im=[System.Drawing.Image]::FromFile($files[$i].FullName); $x=($i%$cols)*$thumbW; $y=[Math]::Floor($i/$cols)*$thumbH; $g.DrawImage($im,$x,$y,$thumbW,$thumbH); $im.Dispose() }
$out=Join-Path (Get-Location) 'party-lp-contact-sheet.jpg'; $sheet.Save($out,[System.Drawing.Imaging.ImageFormat]::Jpeg); $g.Dispose(); $sheet.Dispose(); Write-Output $out
