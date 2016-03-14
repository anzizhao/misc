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
# readDir .
