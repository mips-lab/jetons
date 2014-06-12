<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style type="text/css">
    html, body, div { margin: 0; border: 0 none; padding: 0; }
    html, body, #wrapper, #left, #right { height: 100%; min-height: 100%; }
    #wrapper { margin: 0 auto; oveflow: hidden; width: 960px;  }
    #left { background: white; float: left; width: 260px; }
    #right { background: white; margin-left: 260px;  }
    #previewCnt { background: white; padding:1%; min-height: 300px;   }
    #codeCnt {  background:white; padding:1%; min-height: 300px; }
    .menu {  margin: 3%; background: white; border:1px solid silver; overflow:auto}
    .preview { background: white;  min-height: 300px;border:1px solid silver; }
    .codein { background: white;  min-height: 100%;border:1px solid silver; overflow:auto}
    .header { padding: 5px 8px; border-bottom: 1px solid silver; background: #0b3175; color: white; font-family: Arial, Verdana, Helvetica; font-weight: bold;}
    .subheader { padding: 2px 8px; border-bottom: 1px solid silver; background: gray; color: white; font-family: Arial, Verdana, Helvetica; font-weight: bold;font-size:8pt;}
    .headernob {border-bottom: 0 }
    .cnt { padding: 8px; }
    .item { border-top: 1px solid silver; padding: 3px; font-size: 0.8em; font-family: "Arial Narrow", Arial, Verdana;}
    .menu a { text-decoration: none; color: black; }
    .menu a:visited { text-decoration: none; color: gray; }
    .hi { background: #86c3ff;  }
    a .hi { color: black !important; }
    .filename { float: right; color: white; font-weight: normal;font-size:0.7em;}
    .filename a {color: white; text-decoration: none;}
    .filename a:visited {color: white;}
    .cntsmaller { font-size:0.7em; }
  </style>

  <title>PHP QR Code - Examples</title>
</head>
<body>
  <div id="wrapper">
    <div id="left">
        <div class="menu">
            <div class="header headernob">Examples Menu</div>
                        <div class="subheader headernob">Standard API Basics - Quick Start</div>
                        <a href="index.php?example=001"><div class="item hi "><b>001</b> PNG Output Browser</div></a>
                        <a href="index.php?example=002"><div class="item"><b>002</b> Using outputed PNG</div></a>
                        <a href="index.php?example=003"><div class="item"><b>003</b> Parametrised PNG generator...</div></a>
                        <a href="index.php?example=004"><div class="item"><b>004</b> ... and how to use it</div></a>
                        <a href="index.php?example=005"><div class="item"><b>005</b> PNG saved to file</div></a>
                        <a href="index.php?example=006"><div class="item"><b>006</b> Configuring ECC level</div></a>
                        <a href="index.php?example=007"><div class="item"><b>007</b> Configuring pixel size</div></a>
                        <a href="index.php?example=008"><div class="item"><b>008</b> Configuring frame size</div></a>
                        <a href="index.php?example=010"><div class="item"><b>010</b> Using merged lib version</div></a>
                        <a href="index.php?example=020"><div class="item"><b>020</b> Content - Phone Number</div></a>
                        <a href="index.php?example=021"><div class="item"><b>021</b> Content - SMS App</div></a>
                        <a href="index.php?example=022"><div class="item"><b>022</b> Content - Email simple</div></a>
                        <a href="index.php?example=023"><div class="item"><b>023</b> Content - Email extended</div></a>
                        <a href="index.php?example=024"><div class="item"><b>024</b> Content - Skype call</div></a>
                        <a href="index.php?example=025"><div class="item"><b>025</b> Content - Business Card, simple</div></a>
                        <a href="index.php?example=026"><div class="item"><b>026</b> Content - Business Card, detailed</div></a>
                        <a href="index.php?example=027"><div class="item"><b>027</b> Content - Business Card, photo</div></a>
                                    <div class="subheader headernob">Plugins API Basics - Quick Start</div>
                        <a href="index.php?example=100"><div class="item"><b>100</b> --- ToDo ---</div></a>
                                    <div class="subheader headernob">Standard API - Vector Graphics</div>
                        <a href="index.php?example=201"><div class="item"><b>201</b> SVG basic output</div></a>
                        <a href="index.php?example=202"><div class="item"><b>202</b> SVG confguring output</div></a>
                        <a href="index.php?example=203"><div class="item"><b>203</b> SVG save to file</div></a>
                        <a href="index.php?example=204"><div class="item"><b>204</b> Compressed SVGZ save support</div></a>
                        <a href="index.php?example=211"><div class="item"><b>211</b> CANVAS basic</div></a>
                        <a href="index.php?example=212"><div class="item"><b>212</b> CANVAS rendered on custom tag</div></a>
                                    <div class="subheader headernob">Plugins API - Vector Graphics</div>
                        <a href="index.php?example=300"><div class="item"><b>300</b> --- ToDo ---</div></a>
                                    <div class="subheader headernob">Standard API - Debug &amp; Custom Dev</div>
                        <a href="index.php?example=701"><div class="item"><b>701</b> Text output</div></a>
                        <a href="index.php?example=702"><div class="item"><b>702</b> Text output - ASCII ART</div></a>
                        <a href="index.php?example=703"><div class="item"><b>703</b> Raw Code output</div></a>
                        <a href="index.php?example=704"><div class="item"><b>704</b> Raw Code debug</div></a>
                        <a href="index.php?example=711"><div class="item"><b>711</b> Custom GD2 JPEG renderer</div></a>
                        <a href="index.php?example=712"><div class="item"><b>712</b> Custom GD2 debug renderer</div></a>
                        <a href="index.php?example=721"><div class="item"><b>721</b> Vector debug - areas</div></a>
                        <a href="index.php?example=722"><div class="item"><b>722</b> Vector debug - edges</div></a>
                        <a href="index.php?example=723"><div class="item"><b>723</b> Vector debug - paths renderer</div></a>
                                    <div class="subheader headernob">Plugins API - Debug &amp; Custom Dev</div>
                        <a href="index.php?example=800"><div class="item"><b>800</b> --- ToDo ---</div></a>
                                </div>
        <br />
    </div>

    <div id="right">
        <div id="previewCnt">
            <div class="preview">
                <div class="header">Result</div>
                <iframe style="border:0; background:#e8f7ff;width:100%;height:300px" src="example_001_simple_png_output.php"></iframe>
            </div>
        </div>
        <div id="codeCnt">
            <div class="codein">
                <div class="header">Sourcecode <div class="filename"><a href="example_001_simple_png_output.php">example_001_simple_png_output.php</a></div></div>
                <div class="cnt cntsmaller" style="overflow:auto;white-space:nowrap;"><code><span style="color: #000000">
<span style="color: #0000BB">&lt;?php<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700">include(</span><span style="color: #DD0000">'../lib/full/qrlib.php'</span><span style="color: #007700">);<br />&nbsp;&nbsp;&nbsp;&nbsp;<br />&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000">//&nbsp;outputs&nbsp;image&nbsp;directly&nbsp;into&nbsp;browser,&nbsp;as&nbsp;PNG&nbsp;stream<br />&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #0000BB">QRcode</span><span style="color: #007700">::</span><span style="color: #0000BB">png</span><span style="color: #007700">(</span><span style="color: #DD0000">'PHP&nbsp;QR&nbsp;Code&nbsp;:)'</span><span style="color: #007700">);</span>
</span>
</code></div>
            </div>
        </div>
    </div>
  </div>
</body>
</html>