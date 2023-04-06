#!/bin/sh

for InterfaceName in cases/c*i.php; do
  TraitName=$(echo "$InterfaceName" | sed 's/i\.php$/t.php/')
  OutputName=$(echo "$TraitName" | sed 's/cases/out/')
  php ../bin/iproxy.php "$InterfaceName" >"${OutputName}"
  diff -wu "$OutputName" "$TraitName" >tmp.out
  if [ ! -s tmp.out ]
  then
    echo "Testing $InterfaceName as $OutputName against $TraitName: OK"
  else
    echo
    echo "Testing $InterfaceName as $OutputName against $TraitName:"
    echo "FAIL"
    cat tmp.out
  fi
done
