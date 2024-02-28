<?php
function handlePfp($pfp, $resolution)
{
  if (empty($pfp)) {
    switch ($resolution) {
      case 'tiny':
        $pfp = "https://i.ibb.co/M1CZB9B/770204ae93aa.jpg";
        break;
      case 'medium':
        $pfp = "https://i.ibb.co/C0vr68r/04406b5294bc.jpg";
        break;
      case 'original':
        $pfp = "https://i.ibb.co/PzqTLTF/c7f407ca9fac.jpg";
        break;
    }
  }
  return $pfp;
}