<?php

namespace PHPePub\Core;

/**
 * This should be a complete list of all HTML entities, mapped to their UTF-8 character codes.
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2009- A. Grandt
 * @license   GNU LGPL 2.1
 */
class StaticData
{
    public static $htmlEntities = [
        "&quot;"     => '"',
        // &#34; ((double) quotation mark)
        "&amp;"      => "\x26",
        // &#38; (ampersand)
        "&apos;"     => "\x27",
        // &#39; (apostrophe  => apostrophe-quote)
        "&lt;"       => "\x3C",
        // &#60; (less-than sign)
        "&gt;"       => "\x3E",
        // &#62; (greater-than sign)
        "&nbsp;"     => "\xC2\xA0",
        // &#160; (non-breaking space)
        "&iexcl;"    => "\xC2\xA1",
        // &#161; (inverted exclamation mark)
        "&cent;"     => "\xC2\xA2",
        // &#162; (cent)
        "&pound;"    => "\xC2\xA3",
        // &#163; (pound)
        "&curren;"   => "\xC2\xA4",
        // &#164; (currency)
        "&yen;"      => "\xC2\xA5",
        // &#165; (yen)
        "&brvbar;"   => "\xC2\xA6",
        // &#166; (broken vertical bar)
        "&sect;"     => "\xC2\xA7",
        // &#167; (section)
        "&uml;"      => "\xC2\xA8",
        // &#168; (spacing diaeresis)
        "&copy;"     => "\xC2\xA9",
        // &#169; (copyright)
        "&ordf;"     => "\xC2\xAA",
        // &#170; (feminine ordinal indicator)
        "&laquo;"    => "\xC2\xAB",
        // &#171; (angle quotation mark (left))
        "&not;"      => "\xC2\xAC",
        // &#172; (negation)
        "&shy;"      => "\xC2\xAD",
        // &#173; (soft hyphen)
        "&reg;"      => "\xC2\xAE",
        // &#174; (registered trademark)
        "&macr;"     => "\xC2\xAF",
        // &#175; (spacing macron)
        "&deg;"      => "\xC2\xB0",
        // &#176; (degree)
        "&plusmn;"   => "\xC2\xB1",
        // &#177; (plus-or-minus)
        "&sup2;"     => "\xC2\xB2",
        // &#178; (superscript 2)
        "&sup3;"     => "\xC2\xB3",
        // &#179; (superscript 3)
        "&acute;"    => "\xC2\xB4",
        // &#180; (spacing acute)
        "&micro;"    => "\xC2\xB5",
        // &#181; (micro)
        "&para;"     => "\xC2\xB6",
        // &#182; (paragraph)
        "&middot;"   => "\xC2\xB7",
        // &#183; (middle dot)
        "&cedil;"    => "\xC2\xB8",
        // &#184; (spacing cedilla)
        "&sup1;"     => "\xC2\xB9",
        // &#185; (superscript 1)
        "&ordm;"     => "\xC2\xBA",
        // &#186; (masculine ordinal indicator)
        "&raquo;"    => "\xC2\xBB",
        // &#187; (angle quotation mark (right))
        "&frac14;"   => "\xC2\xBC",
        // &#188; (fraction 1/4)
        "&frac12;"   => "\xC2\xBD",
        // &#189; (fraction 1/2)
        "&frac34;"   => "\xC2\xBE",
        // &#190; (fraction 3/4)
        "&iquest;"   => "\xC2\xBF",
        // &#191; (inverted question mark)
        "&Agrave;"   => "\xC3\x80",
        // &#192; (capital a, grave accent)
        "&Aacute;"   => "\xC3\x81",
        // &#193; (capital a, acute accent)
        "&Acirc;"    => "\xC3\x82",
        // &#194; (capital a, circumflex accent)
        "&Atilde;"   => "\xC3\x83",
        // &#195; (capital a, tilde)
        "&Auml;"     => "\xC3\x84",
        // &#196; (capital a, umlaut mark)
        "&Aring;"    => "\xC3\x85",
        // &#197; (capital a, ring)
        "&AElig;"    => "\xC3\x86",
        // &#198; (capital ae)
        "&Ccedil;"   => "\xC3\x87",
        // &#199; (capital c, cedilla)
        "&Egrave;"   => "\xC3\x88",
        // &#200; (capital e, grave accent)
        "&Eacute;"   => "\xC3\x89",
        // &#201; (capital e, acute accent)
        "&Ecirc;"    => "\xC3\x8A",
        // &#202; (capital e, circumflex accent)
        "&Euml;"     => "\xC3\x8B",
        // &#203; (capital e, umlaut mark)
        "&Igrave;"   => "\xC3\x8C",
        // &#204; (capital i, grave accent)
        "&Iacute;"   => "\xC3\x8D",
        // &#205; (capital i, acute accent)
        "&Icirc;"    => "\xC3\x8E",
        // &#206; (capital i, circumflex accent)
        "&Iuml;"     => "\xC3\x8F",
        // &#207; (capital i, umlaut mark)
        "&ETH;"      => "\xC3\x90",
        // &#208; (capital eth, Icelandic)
        "&Ntilde;"   => "\xC3\x91",
        // &#209; (capital n, tilde)
        "&Ograve;"   => "\xC3\x92",
        // &#210; (capital o, grave accent)
        "&Oacute;"   => "\xC3\x93",
        // &#211; (capital o, acute accent)
        "&Ocirc;"    => "\xC3\x94",
        // &#212; (capital o, circumflex accent)
        "&Otilde;"   => "\xC3\x95",
        // &#213; (capital o, tilde)
        "&Ouml;"     => "\xC3\x96",
        // &#214; (capital o, umlaut mark)
        "&times;"    => "\xC3\x97",
        // &#215; (multiplication)
        "&Oslash;"   => "\xC3\x98",
        // &#216; (capital o, slash)
        "&Ugrave;"   => "\xC3\x99",
        // &#217; (capital u, grave accent)
        "&Uacute;"   => "\xC3\x9A",
        // &#218; (capital u, acute accent)
        "&Ucirc;"    => "\xC3\x9B",
        // &#219; (capital u, circumflex accent)
        "&Uuml;"     => "\xC3\x9C",
        // &#220; (capital u, umlaut mark)
        "&Yacute;"   => "\xC3\x9D",
        // &#221; (capital y, acute accent)
        "&THORN;"    => "\xC3\x9E",
        // &#222; (capital THORN, Icelandic)
        "&szlig;"    => "\xC3\x9F",
        // &#223; (small sharp s, German)
        "&agrave;"   => "\xC3\xA0",
        // &#224; (small a, grave accent)
        "&aacute;"   => "\xC3\xA1",
        // &#225; (small a, acute accent)
        "&acirc;"    => "\xC3\xA2",
        // &#226; (small a, circumflex accent)
        "&atilde;"   => "\xC3\xA3",
        // &#227; (small a, tilde)
        "&auml;"     => "\xC3\xA4",
        // &#228; (small a, umlaut mark)
        "&aring;"    => "\xC3\xA5",
        // &#229; (small a, ring)
        "&aelig;"    => "\xC3\xA6",
        // &#230; (small ae)
        "&ccedil;"   => "\xC3\xA7",
        // &#231; (small c, cedilla)
        "&egrave;"   => "\xC3\xA8",
        // &#232; (small e, grave accent)
        "&eacute;"   => "\xC3\xA9",
        // &#233; (small e, acute accent)
        "&ecirc;"    => "\xC3\xAA",
        // &#234; (small e, circumflex accent)
        "&euml;"     => "\xC3\xAB",
        // &#235; (small e, umlaut mark)
        "&igrave;"   => "\xC3\xAC",
        // &#236; (small i, grave accent)
        "&iacute;"   => "\xC3\xAD",
        // &#237; (small i, acute accent)
        "&icirc;"    => "\xC3\xAE",
        // &#238; (small i, circumflex accent)
        "&iuml;"     => "\xC3\xAF",
        // &#239; (small i, umlaut mark)
        "&eth;"      => "\xC3\xB0",
        // &#240; (small eth, Icelandic)
        "&ntilde;"   => "\xC3\xB1",
        // &#241; (small n, tilde)
        "&ograve;"   => "\xC3\xB2",
        // &#242; (small o, grave accent)
        "&oacute;"   => "\xC3\xB3",
        // &#243; (small o, acute accent)
        "&ocirc;"    => "\xC3\xB4",
        // &#244; (small o, circumflex accent)
        "&otilde;"   => "\xC3\xB5",
        // &#245; (small o, tilde)
        "&ouml;"     => "\xC3\xB6",
        // &#246; (small o, umlaut mark)
        "&divide;"   => "\xC3\xB7",
        // &#247; (division)
        "&oslash;"   => "\xC3\xB8",
        // &#248; (small o, slash)
        "&ugrave;"   => "\xC3\xB9",
        // &#249; (small u, grave accent)
        "&uacute;"   => "\xC3\xBA",
        // &#250; (small u, acute accent)
        "&ucirc;"    => "\xC3\xBB",
        // &#251; (small u, circumflex accent)
        "&uuml;"     => "\xC3\xBC",
        // &#252; (small u, umlaut mark)
        "&yacute;"   => "\xC3\xBD",
        // &#253; (small y, acute accent)
        "&thorn;"    => "\xC3\xBE",
        // &#254; (small thorn, Icelandic)
        "&yuml;"     => "\xC3\xBF",
        // &#255; (small y, umlaut mark)
        "&OElig;"    => "\xC5\x92",
        // &#338; (capital ligature OE)
        "&oelig;"    => "\xC5\x93",
        // &#339; (small ligature oe)
        "&Scaron;"   => "\xC5\xA0",
        // &#352; (capital S with caron)
        "&scaron;"   => "\xC5\xA1",
        // &#353; (small S with caron)
        "&Yuml;"     => "\xC5\xB8",
        // &#376; (capital Y with diaeres)
        "&fnof;"     => "\xC6\x92",
        // &#402; (f with hook)
        "&circ;"     => "\xCB\x86",
        // &#710; (modifier letter circumflex accent)
        "&tilde;"    => "\xCB\x9C",
        // &#732; (small tilde)
        "&Alpha;"    => "\xCE\x91",
        // &#913; (Alpha)
        "&Beta;"     => "\xCE\x92",
        // &#914; (Beta)
        "&Gamma;"    => "\xCE\x93",
        // &#915; (Gamma)
        "&Delta;"    => "\xCE\x94",
        // &#916; (Delta)
        "&Epsilon;"  => "\xCE\x95",
        // &#917; (Epsilon)
        "&Zeta;"     => "\xCE\x96",
        // &#918; (Zeta)
        "&Eta;"      => "\xCE\x97",
        // &#919; (Eta)
        "&Theta;"    => "\xCE\x98",
        // &#920; (Theta)
        "&Iota;"     => "\xCE\x99",
        // &#921; (Iota)
        "&Kappa;"    => "\xCE\x9A",
        // &#922; (Kappa)
        "&Lambda;"   => "\xCE\x9B",
        // &#923; (Lambda)
        "&Mu;"       => "\xCE\x9C",
        // &#924; (Mu)
        "&Nu;"       => "\xCE\x9D",
        // &#925; (Nu)
        "&Xi;"       => "\xCE\x9E",
        // &#926; (Xi)
        "&Omicron;"  => "\xCE\x9F",
        // &#927; (Omicron)
        "&Pi;"       => "\xCE\xA0",
        // &#928; (Pi)
        "&Rho;"      => "\xCE\xA1",
        // &#929; (Rho)
        "&Sigma;"    => "\xCE\xA3",
        // &#931; (Sigma)
        "&Tau;"      => "\xCE\xA4",
        // &#932; (Tau)
        "&Upsilon;"  => "\xCE\xA5",
        // &#933; (Upsilon)
        "&Phi;"      => "\xCE\xA6",
        // &#934; (Phi)
        "&Chi;"      => "\xCE\xA7",
        // &#935; (Chi)
        "&Psi;"      => "\xCE\xA8",
        // &#936; (Psi)
        "&Omega;"    => "\xCE\xA9",
        // &#937; (Omega)
        "&alpha;"    => "\xCE\xB1",
        // &#945; (alpha)
        "&beta;"     => "\xCE\xB2",
        // &#946; (beta)
        "&gamma;"    => "\xCE\xB3",
        // &#947; (gamma)
        "&delta;"    => "\xCE\xB4",
        // &#948; (delta)
        "&epsilon;"  => "\xCE\xB5",
        // &#949; (epsilon)
        "&zeta;"     => "\xCE\xB6",
        // &#950; (zeta)
        "&eta;"      => "\xCE\xB7",
        // &#951; (eta)
        "&theta;"    => "\xCE\xB8",
        // &#952; (theta)
        "&iota;"     => "\xCE\xB9",
        // &#953; (iota)
        "&kappa;"    => "\xCE\xBA",
        // &#954; (kappa)
        "&lambda;"   => "\xCE\xBB",
        // &#955; (lambda)
        "&mu;"       => "\xCE\xBC",
        // &#956; (mu)
        "&nu;"       => "\xCE\xBD",
        // &#957; (nu)
        "&xi;"       => "\xCE\xBE",
        // &#958; (xi)
        "&omicron;"  => "\xCE\xBF",
        // &#959; (omicron)
        "&pi;"       => "\xCF\x80",
        // &#960; (pi)
        "&rho;"      => "\xCF\x81",
        // &#961; (rho)
        "&sigmaf;"   => "\xCF\x82",
        // &#962; (sigmaf)
        "&sigma;"    => "\xCF\x83",
        // &#963; (sigma)
        "&tau;"      => "\xCF\x84",
        // &#964; (tau)
        "&upsilon;"  => "\xCF\x85",
        // &#965; (upsilon)
        "&phi;"      => "\xCF\x86",
        // &#966; (phi)
        "&chi;"      => "\xCF\x87",
        // &#967; (chi)
        "&psi;"      => "\xCF\x88",
        // &#968; (psi)
        "&omega;"    => "\xCF\x89",
        // &#969; (omega)
        "&thetasym;" => "\xCF\x91",
        // &#977; (theta symbol)
        "&upsih;"    => "\xCF\x92",
        // &#978; (upsilon symbol)
        "&piv;"      => "\xCF\x96",
        // &#982; (pi symbol)
        "&ensp;"     => "\xE2\x80\x82",
        // &#8194; (en space)
        "&emsp;"     => "\xE2\x80\x83",
        // &#8195; (em space)
        "&thinsp;"   => "\xE2\x80\x89",
        // &#8201; (thin space)
        "&zwnj;"     => "‌\xE2\x80\x8C",
        // &#8204; (zero width non-joiner)
        "&zwj;"      => "\xE2\x80\x8D‍",
        // &#8205; (zero width joiner)
        "&lrm;"      => "‎\xE2\x80\x8E",
        // &#8206; (left-to-right mark)
        "&rlm;"      => "\xE2\x80\x8F",
        // &#8207; (right-to-left mark)
        "&ndash;"    => "\xE2\x80\x93",
        // &#8211; (en dash)
        "&mdash;"    => "\xE2\x80\x94",
        // &#8212; (em dash)
        "&lsquo;"    => "\xE2\x80\x98",
        // &#8216; (left single quotation mark)
        "&rsquo;"    => "\xE2\x80\x99",
        // &#8217; (right single quotation mark)
        "&sbquo;"    => "\xE2\x80\x9A",
        // &#8218; (single low-9 quotation mark)
        "&ldquo;"    => "\xE2\x80\x9C",
        // &#8220; (left double quotation mark)
        "&rdquo;"    => "\xE2\x80\x9D",
        // &#8221; (right double quotation mark)
        "&bdquo;"    => "\xE2\x80\x9E",
        // &#8222; (double low-9 quotation mark)
        "&dagger;"   => "\xE2\x80\xA0",
        // &#8224; (dagger)
        "&Dagger;"   => "\xE2\x80\xA1",
        // &#8225; (double dagger)
        "&bull;"     => "\xE2\x80\xA2",
        // &#8226; (bullet)
        "&hellip;"   => "\xE2\x80\xA6",
        // &#8230; (horizontal ellipsis)
        "&permil;"   => "\xE2\x80\xB0",
        // &#8240; (per mille)
        "&prime;"    => "\xE2\x80\xB2",
        // &#8242; (minutes or prime)
        "&Prime;"    => "\xE2\x80\xB3",
        // &#8243; (seconds or Double Prime)
        "&lsaquo;"   => "\xE2\x80\xB9",
        // &#8249; (single left angle quotation)
        "&rsaquo;"   => "\xE2\x80\xBA",
        // &#8250; (single right angle quotation)
        "&oline;"    => "\xE2\x80\xBE",
        // &#8254; (overline)
        "&frasl;"    => "\xE2\x81\x84",
        // &#8260; (fraction slash)
        "&euro;"     => "\xE2\x82\xAC",
        // &#8364; (euro)
        "&image;"    => "\xE2\x84\x91",
        // &#8465; (blackletter capital I)
        "&weierp;"   => "\xE2\x84\x98",
        // &#8472; (script capital P)
        "&real;"     => "\xE2\x84\x9C",
        // &#8476; (blackletter capital R)
        "&trade;"    => "\xE2\x84\xA2",
        // &#8482; (trademark)
        "&alefsym;"  => "\xE2\x84\xB5",
        // &#8501; (alef)
        "&larr;"     => "\xE2\x86\x90",
        // &#8592; (left arrow)
        "&uarr;"     => "\xE2\x86\x91",
        // &#8593; (up arrow)
        "&rarr;"     => "\xE2\x86\x92",
        // &#8594; (right arrow)
        "&darr;"     => "\xE2\x86\x93",
        // &#8595; (down arrow)
        "&harr;"     => "\xE2\x86\x94",
        // &#8596; (left right arrow)
        "&crarr;"    => "\xE2\x86\xB5",
        // &#8629; (carriage return arrow)
        "&lArr;"     => "\xE2\x87\x90",
        // &#8656; (left double arrow)
        "&uArr;"     => "\xE2\x87\x91",
        // &#8657; (up double arrow)
        "&rArr;"     => "\xE2\x87\x92",
        // &#8658; (right double arrow)
        "&dArr;"     => "\xE2\x87\x93",
        // &#8659; (down double arrow)
        "&hArr;"     => "\xE2\x87\x94",
        // &#8660; (left right double arrow)
        "&forall;"   => "\xE2\x88\x80",
        // &#8704; (for all)
        "&part;"     => "\xE2\x88\x82",
        // &#8706; (partial differential)
        "&exist;"    => "\xE2\x88\x83",
        // &#8707; (there exists)
        "&empty;"    => "\xE2\x88\x85",
        // &#8709; (empty set)
        "&nabla;"    => "\xE2\x88\x87",
        // &#8711; (backward difference)
        "&isin;"     => "\xE2\x88\x88",
        // &#8712; (element of)
        "&notin;"    => "\xE2\x88\x89",
        // &#8713; (not an element of)
        "&ni;"       => "\xE2\x88\x8B",
        // &#8715; (ni => contains as member)
        "&prod;"     => "\xE2\x88\x8F",
        // &#8719; (n-ary product)
        "&sum;"      => "\xE2\x88\x91",
        // &#8721; (n-ary sumation)
        "&minus;"    => "\xE2\x88\x92",
        // &#8722; (minus)
        "&lowast;"   => "\xE2\x88\x97",
        // &#8727; (asterisk operator)
        "&radic;"    => "\xE2\x88\x9A",
        // &#8730; (square root)
        "&prop;"     => "\xE2\x88\x9D",
        // &#8733; (proportional to)
        "&infin;"    => "\xE2\x88\x9E",
        // &#8734; (infinity)
        "&ang;"      => "\xE2\x88\xA0",
        // &#8736; (angle)
        "&and;"      => "\xE2\x88\xA7",
        // &#8743; (logical and)
        "&or;"       => "\xE2\x88\xA8",
        // &#8744; (logical or)
        "&cap;"      => "\xE2\x88\xA9",
        // &#8745; (intersection)
        "&cup;"      => "\xE2\x88\xAA",
        // &#8746; (union)
        "&int;"      => "\xE2\x88\xAB",
        // &#8747; (integral)
        "&there4;"   => "\xE2\x88\xB4",
        // &#8756; (therefore)
        "&sim;"      => "\xE2\x88\xBC",
        // &#8764; (similar to)
        "&cong;"     => "\xE2\x89\x85",
        // &#8773; (congruent to)
        "&asymp;"    => "\xE2\x89\x88",
        // &#8776; (approximately equal)
        "&ne;"       => "\xE2\x89\xA0",
        // &#8800; (not equal)
        "&equiv;"    => "\xE2\x89\xA1",
        // &#8801; (equivalent)
        "&le;"       => "\xE2\x89\xA4",
        // &#8804; (less or equal)
        "&ge;"       => "\xE2\x89\xA5",
        // &#8805; (greater or equal)
        "&sub;"      => "\xE2\x8A\x82",
        // &#8834; (subset of)
        "&sup;"      => "\xE2\x8A\x83",
        // &#8835; (superset of)
        "&nsub;"     => "\xE2\x8A\x84",
        // &#8836; (not subset of)
        "&sube;"     => "\xE2\x8A\x86",
        // &#8838; (subset or equal)
        "&supe;"     => "\xE2\x8A\x87",
        // &#8839; (superset or equal)
        "&oplus;"    => "\xE2\x8A\x95",
        // &#8853; (circled plus)
        "&otimes;"   => "\xE2\x8A\x87",
        // &#8855; (circled times)
        "&perp;"     => "\xE2\x8A\xA5",
        // &#8869; (perpendicular)
        "&sdot;"     => "\xE2\x8C\x85",
        // &#8901; (dot operator)
        "&lceil;"    => "\xE2\x8C\x88",
        // &#8968; (left ceiling)
        "&rceil;"    => "\xE2\x8C\x89",
        // &#8969; (right ceiling)
        "&lfloor;"   => "\xE2\x8C\x8A",
        // &#8970; (left floor)
        "&rfloor;"   => "\xE2\x8C\x8B",
        // &#8971; (right floor)
        "&lang;"     => "\xE2\x8C\xA9",
        // &#9001; (left angle bracket => bra)
        "&rang;"     => "\xE2\x8C\xAA",
        // &#9002; (right angle bracket => ket)
        "&loz;"      => "\xE2\x97\x8A",
        // &#9674; (lozenge)
        "&spades;"   => "\xE2\x99\xA0",
        // &#9824; (spade)
        "&clubs;"    => "\xE2\x99\xA3",
        // &#9827; (club)
        "&hearts;"   => "\xE2\x99\xA5",
        // &#9829; (heart)
        "&diams;"    => "\xE2\x99\xA6",
    ];

    public static $mimetypes = ["js"  => "application/x-javascript", "swf" => "application/x-shockwave-flash", "xht" => "application/xhtml+xml", "xhtml" => "application/xhtml+xml", "zip" => "application/zip", "aif" => "audio/x-aiff", "aifc" => "audio/x-aiff", "aiff" => "audio/x-aiff", "au" => "audio/basic", "kar" => "audio/midi", "m3u" => "audio/x-mpegurl", "mid" => "audio/midi", "midi" => "audio/midi", "mp2" => "audio/mpeg", "mp3" => "audio/mpeg", "mpga" => "audio/mpeg", "oga" => "audio/ogg", "ogg" => "audio/ogg", "ra" => "audio/x-realaudio", "ram" => "audio/x-pn-realaudio", "rm" => "audio/x-pn-realaudio", "rpm" => "audio/x-pn-realaudio-plugin", "snd" => "audio/basic", "wav" => "audio/x-wav", "bmp" => "image/x-windows-bmp", "cpt" => "image/tiff", "djv" => "image/vnd.djvu", "djvu" => "image/vnd.djvu", "gif" => "image/gif", "ief" => "image/ief", "jpe" => "image/jpeg", "jpeg" => "image/jpeg", "jpg" => "image/jpeg", "lbm" => "image/x-ilbm", "ilbm" => "image/x-ilbm", "pbm" => "image/x-portable-bitmap", "pgm" => "image/x-portable-graymap", "png" => "image/png", "pnm" => "image/x-portable-anymap", "ppm" => "image/x-portable-pixmap", "ras" => "image/x-cmu-raster", "rgb" => "image/x-rgb", "tif" => "image/tif", "tiff" => "image/tiff", "wbmp" => "image/vnd.wap.wbmp", "xbm" => "image/x-xbitmap", "xpm" => "image/x-xpixmap", "xwd" => "image/x-windowdump", "asc" => "text/plain", "css" => "text/css", "etx" => "text/x-setext", "htm" => "text/html", "html" => "text/html", "rtf" => "text/rtf", "rtx" => "text/richtext", "sgm" => "text/sgml", "sgml" => "text/sgml", "tsv" => "text/tab-seperated-values", "txt" => "text/plain", "wml" => "text/vnd.wap.wml", "wmls" => "text/vnd.wap.wmlscript", "xml" => "text/xml", "xsl" => "text/xml", "avi" => "video/x-msvideo", "mov" => "video/quicktime", "movie" => "video/x-sgi-movie", "mp4" => "video/mp4", "mpe" => "video/mpeg", "mpeg" => "video/mpeg", "mpg" => "video/mpeg", "mxu" => "video/vnd.mpegurl", "ogv" => "video/ogg", "qt" => "video/quicktime", "webm" => "video/webm"];

    // These are the ONLY allowed types in that these are the ones ANY reader must support, any other MUST have the fallback attribute pointing to one of these.
    public static $coreMediaTypes = ["image/gif", "image/jpeg", "image/png", "image/svg+xml", "application/xhtml+xml", "application/x-dtbook+xml", "application/xml", "application/x-dtbncx+xml", "text/css", "text/x-oeb1-css", "text/x-oeb1-document"];

    public static $opsContentTypes = ["application/xhtml+xml", "application/x-dtbook+xml", "application/xml", "application/x-dtbncx+xml", "text/x-oeb1-document"];

    public static $forbiddenCharacters = ["?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", '"', "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%"];

    public static $namespaces = ["xsi" => "http://www.w3.org/2001/XMLSchema-instance", "opf" => "http://www.idpf.org/2007/opf", "dcterms" => "http://purl.org/dc/terms/", "dc" => "http://purl.org/dc/elements/1.1/"];
}
