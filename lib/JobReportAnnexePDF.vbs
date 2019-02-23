Set myArgs = WScript.Arguments.Unnamed

dim lstargs
dim cmd




For i = 2 to myargs.count -1
  lstargs = lstargs & " """ & myArgs.item(i) & """"
Next 



cmd = """C:\Program Files (x86)\CoolUtils\PDF Combine Pro\PDFCombinePro.exe"""

param = " -kfs -c PDF -pdflimit 0 -HeadText """" -HeadAlign r -HeadFont ""Arial"" -HeadSize 6 -FootText ""[Page Counter] / [Total Pages]"" -FootAlign r -FootFont ""Arial"" -FootSize 6 -bookmark -npr 0,0 -bstyle f -bpdf -PDFAuthor MRSAS -PDFSubject Report_" & WScript.Arguments.item(1) & " -PDFProducer Softplicity -toclinestyle D -toclinecolor silver -tocfont [Calibri,11,black] -tocmargins [0.80,0.80,0.80,0.80] -pc M -TM 0.3 -LM 0.3 -BM 0.3 -RM 0.3 -ps A4"

cmdline = cmd & lstargs & param


Set WshShell = WScript.CreateObject("WScript.Shell")
WshShell.Run cmdline