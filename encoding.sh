#!/bin/zsh
#enter input encoding here
FROM_ENCODING="ISO-8859-1"
#output encoding(UTF-8)
TO_ENCODING="UTF-8"
#convert
CONVERT="iconv -f $FROM_ENCODING -t $TO_ENCODING"
#loop to convert multiple files
for  file  in  raw/.groupes; do
  if [ ! -d $file ]; then
    iconv -f $FROM_ENCODING -t $TO_ENCODING $file -o ${file}.new
    rm ${file}
    mv ${file}.new ${file}
  fi
done
exit 0
