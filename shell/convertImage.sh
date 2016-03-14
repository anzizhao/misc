#! /bin/bash

function readDir()
{
    dir=$1
    if [ ! -d "$dir" ]; then
       return ; 
    fi
    files=$(ls $dir)
    for file in ${files}
    do
        echo ${dir}/${file}
    done
}


dir="."
width=320
quality=50
if [ -n "$1" ]; then
    dir=$1
fi

if [ -n "$2" ]; then
    width=$2
fi


if [ ! -d "$dir" ]; then
    echo "$dir not a directory"; 
    exit 0;
fi

for filePath in $(readDir $dir)
do
    #只对原图做处理
    #set -x
    if [[ ${filePath} =~ "_" ]]; then
        continue;
    fi
    #set +x
    filename=$(basename ${filePath})
    purename=${filename%.*}
    suffix=${filename##*.}
    tempfile=${dir}/${purename}_q${quality}.${suffix}
    #sample 的效果
    #convert -sample ${width}  ${filePath} ${dir}/${purename}_q${width}.${suffix}
    #resize 大小会好点 且质量没有问题
    #convert -resize ${width}  ${filePath} ${dir}/${purename}_${width}.${suffix}
    convert -quality ${quality} ${filePath} ${tempfile}
    convert -resize ${width} ${tempfile} ${dir}/${purename}_q${quality}_${width}.${suffix}
    rm ${tempfile}
    #echo ${dir}/${purename}
done
