#!/bin/bash
# Name:	 lrcdis （外挂式显歌词脚本）
# License:  GPLv3
# Credit:   xiooli,solcomo,bones7456,oldherl  (2008-2009)
# Encoding: UTF-8
# Thanks to: 搜狗歌词搜索






Version=090908
#是否开启调试信息
Debug=0
Conf_file=~/.config/lrcdis.conf
Cookie_file=/dev/shm/lrcdis-cookie-file-$USER-"`date +"%s.%N"`-$RANDOM"
NoFileLrc=yes
#=========================定义函数=============================

INIT() {
	#程序初始化函数

	#没有参数，正确执行如果没有配置文件则会产生配置文件并加载配置，有配置文件则加载配置文件

	[ -f "$Conf_file" ] || cat<< EOF > "$Conf_file"
#===================LRCDIS-CONFIG================
#保存lrc文件的文件夹
Lrcdir=~/.lyrics
#显示模式： osd|notify|fifo|title|cli|kdialog|echo (将歌词输出到一个管道文件，以便用其他命令访问)
Dismode=osd
#播放器类型： mpd|mocp|muine|audacious|juk|amarok|exaile|gmusicbrowser|quodlibet|qmmp|rhythmbox|banshee|audacious2|xmms2
Player=rhythmbox
#获取歌曲名称方式： id3(id3标签方式)|filename(文件名方式)
Readname=id3
#自身时间发生器产生时间的间隔（最好小于1秒）
Sleeptm=0.6
#======================[cli]=====================
#命令行显示时的参数
#一屏显示行数
Row=10
#歌曲标题的颜色（见最后的颜色定义）
Titlecolor=green
#歌曲歌词的颜色（见最后的颜色定义）
Lyricscolor=blue
#==============[osd, notify & kdialog]=============
#osd显示时的参数
#osd显示位置：top, bottom
Vposition=top
#osd对齐位置：left, right, center
Halignment=center
#osd最长显示时间（超过则隐藏,单位毫秒？）
Timeout=10000
#span size
Size=20000
#字体颜色： green|white|blue|yellow|grey|...
#注意，这个颜色与cli模式的颜色不是一回事
Foreground=green
#动画效果： off, on
Animations=off
#是否加新行，以免挡住panel等: off|on
Addnewline=off
#notify模式的图标文件名(只支持绝对路径)
NotifyIcon=""
#kdialog显示的timeout
Ktimeout=5
#====================[lrc]======================
#从何处下载歌词: SOGOU_URI,BAIDU_URI,QIANQIAN_URI
Uri=SOGOU_URI
#是否检查歌词文件，yes/no，yes将会检查歌词文件是否正确，若不正确则重新下载歌词
Checklrc=yes
#下载歌词的最大重试次数,不宜过大
Maxdowntimes=4
#只是用歌名匹配 ( no ,yes)
OnlySong=yes
#================================================
#定义颜色（仅在cli模式下使用）
black="30"
red="31"
green="32"
yellow="33"
blue="34"
magenta="35"
cyan="36"
white="37"             
#======================END========================
EOF

	. "$Conf_file"
}

DEBUGECHO() {
	[ "$Debug" = 1 ] && echo "$@" >&2
}

DO_NAME() {                                                  #echo返回去掉后缀的歌手歌曲名字
	#  1 argument, the filename
	#  removing .mp3/.wma/.ogg/.ape/.flac etc.
	local	a   song singer
	a="`basename "$1"`"
	#理论上说以 . 分隔的最后那部分是扩展名
	a="${a%.[a-zA-Z0-9]*}"
	#a="${a/%.mp3/}"; a="${a/%.MP3/}"
	#a="${a/%.wma/}"; a="${a/%.WMA/}"
	#a="${a/%.ogg/}"; a="${a/%.OGG/}"
	#a="${a/%.ape/}"; a="${a/%.APE/}"
	#a="${a/%.flac/}"; a="${a/%.FLAC/}"
	# 去掉前置的一位或两位数字和".",一般是专辑中的序号
	a="${a/#[0-9]./}"; a="${a/#[0-9][0-9]./}"
	
#	a=`echo $a |iconv -c -f gbk -t utf-8`
	singer=${a%%-*}
	song=${a#*-}
	song=`echo $song |tr ' ' '+'`
	singer=`echo $singer |tr ',' '&' |tr '+' '&'|tr  ' ' '_'`


	echo "$singer-$song"

}

USAGE() {
	#显示帮助信息的函数

	#没有参数，执行显示帮助信息

	cat << EOF
lrcdis $Version (http://code.google.com/p/lrcdis)
用法： $0 [选项]
选项：

	-C, --config <配置文件路径>
		指定配置文件 (默认： ~/.config/lrcdis.conf)
	-s, --save-dir <保存目录>
		指定歌词的保存目录 (默认： ~/.lyrics)
	-d, --download <歌曲名>
		仅下载歌词, 保存至歌词目录
	-E, --edit-conf
		进入编辑配置文件的模式
	-p, --player <播放器>
		优先检查此播放器，如果此播放器未运行则继续检查其他播放器
	-m, --mode <显示模式>
		以 <显示模式> 显示歌词，详细参数见下面：
	-m osd
		以osd模式显示歌词(needs gnome-osd)
	-m notify
		以notify模式显示歌词(needs notify-send)
	-m fifo
		将歌词输出到管道文件/dev/shm/lrcfifo
	-m title
		将歌词输出到终端的标题中(支持大多数终端)
	-m kdialog
		将歌词以kdialog的通知模式输出
	-m echo
		将歌词以普通echo模式输出
	-m cli
		以cli模式显示歌词
	-D, --debug
		Debug mode
	-h, --help
		显示本帮助信息并退出
	-v, --version
		显示版本号并退出
备注：不加任何选项则以 $Conf_file 为配置文件初始化运行
EOF
}

###################################
function MultiSinger ()
{
   #参数1 为歌曲歌手

    local singers singer song  regular_e   i
    singers=${1%%-*}
    song=${1#*-}
    regular_e=`echo "$singers" |tr '&' '|'`

    for i  in `echo $singers|tr '&' ' '`
    do

	if [[ -z "$singer" ]];then  
	    singer=\($regular_e\)
	else
	    singer="$singer"'&('$regular_e')'
	fi
    done

    song=`echo "$song" | sed 's:\+:\\\+:g' `
    
    echo "$singer-$song"
    
}







#############################
PLAYER_USERS() {
	#一个参数,播放器进程名,返回该进程可能对应的用户ID.
	id -u | tr -d "\n"
	if [ "$1" = "mpd" ];then
		echo -n ",0" #mpd以root运行算合法
		if id -u mpd >/dev/null 2>&1 ;then
			echo -n ","
			id -u mpd | tr -d "\n" #mpd以mpd用户运行算合法
		fi
	fi
}

PLAYER_RUNNING(){
	#判断播放器进程是否存在
	#一个参数, 播放器名
	#返回0表示进程存在,1表示不存在
	{
	if [ "$1" = "quodlibet" ]; then
		pgrep -fx "python `which quodlibet`" -u "`PLAYER_USERS "$1"`" && return 0
	elif [ "$1" = "banshee" ]; then
		pgrep -x "banshee-1" -u "`PLAYER_USERS "$1"`" && return 0
	elif [ "$1" = "xmms2" ]; then
		pgrep -x "xmms2d" -u "`PLAYER_USERS "$1"`" && return 0
	else
		pgrep -x "$1" -u "`PLAYER_USERS "$1"`" && return 0
	fi
	return 1
	} >/dev/null 2>&1
}

CHECK_PLAYER() {
	#自动检查播放器类型的函数

	#没有参数，返回播放器类型
	
	local players i

	#所有被支持的播放器都保存下面的字符串里面
	#gmusicbrowser需要perl-net-dbus让其支持dbus通信才可以
	players="rhythmbox audacious juk amarok amarokapp exaile banshee gmusicbrowser quodlibet qmmp mocp mpd muine mplayer audacious2 xmms2"
	if PLAYER_RUNNING "$Player";then
		echo -n "$Player"
	else
		for i in $players;do
			DEBUGECHO "$Player   $i"
			if PLAYER_RUNNING "$i";then
				echo -n "$i"|sed "s/amarokapp/amarok/"
				break
			fi
		done
	fi
}







DOWNLRC() {
	#下载歌词的函数

	#参数两个，$1 (str)： 歌曲名字; $2 (int)：欲提取的下载的链接序数
	#没有返回值，正确执行会下载一个 lrc 文件至 Lrcdir

	local nm link full_link gb file

	nm="$1"
	[ ! -d "$Lrcdir" ] && mkdir -p "$Lrcdir"
	file="$Lrcdir/$nm.lrc"
	
	#将歌曲名字转换为urlencode，utf-8的locale必须先转换为gbk的编码
  #	gb="$(echo -n "$nm" | iconv -c -t gbk | od -t x1 -A n -w1000|tr " " "%")"

#	echo $nm   nihaoma 
	LRC123_URL "$nm" 



}



CHECK_LRC() {
	#检查歌词文件是否正确
	
	#参数三个，$1： 歌词文件路径; $2： 当前歌曲的标题（包含题目和/或艺人）
	# $3：检查是否放水（yes严格执行，no则总返回合格）
	#正确返回字符串LRC-RIGHT，反之LRC-WRONG
	
	local ti0 ti1 ar0 ar1 judge ms_compare
#	echo "CHECK_LRC"
#	echo "$1"
	[ ! -f "$1" ] && exit 1
	if [ "${3:-yes}" = "yes" ];then
		ti0="`cat "$1"|grep -o "\[ti:[^\[]*\]"|tr -d "[]"|tr [:upper:] [:lower:]`";ti0="${ti0/ti:}"
	#	ti0=`echo $ti0 |tr ' ' '+'`
		ar0="`cat "$1"|grep -o "\[ar:[^\[]*\]"|tr -d "[]"|tr [:upper:] [:lower:]`";ar0="${ar0/ar:}"
		ti1="${2//*-}";ti1="`echo $ti1|sed 's/^\ \+//;s/\ \+$//'|tr [:upper:] [:lower:]`"
		ti1="`echo $ti1 |tr '+' ' '`"
		[ "${2//-}" = "$2" ] || ar1="${2//-*}" && ar1="`echo $ar1|sed 's/^\ \+//;s/\ \+$//'|tr [:upper:] [:lower:]`"

	#	echo "ti0: $ti0  ar0: $ar0"
	#	echo "ti1: $ti1  ar1: $ar1"
		
		ar0=`echo "$ar0" |tr ' ' '_'`
		ms_compare=`MultiSinger $2`
		ms_compare=${ms_compare%%-*}

#
#		echo $ms_compare
		if [ ! "$ar1" ]; then
			if [ "$ti0" = "$ti1" -o "$ar0" = "$ar1" ]; then
				judge="LRC-RIGHT"
			else
				judge="LRC-WRONG"
			fi
		else
			if [ "$ti0" = "$ti1" -a -n 'echo $ar0|egrep $ms_compare' ]; then
				judge="LRC-RIGHT"
			else	
				judge="LRC-WRONG"
			fi
		fi
	else
		judge="LRC-RIGHT"
	fi
	echo "$judge"
	DEBUGECHO "CHECK_LRC: $judge; Checklrc => $3; File =>Ar:$ar0, Ti:$ti0; Title => Ar:$ar1, Ti:$ti1"
}



function BZMTV_URL ()
{
    #lrc.bzmtv.com  搜索歌词下载
    #一个参数 ， 歌手歌曲名字
    #找不到的时候 将NoFileLrc标记yes
 
 #   echo "BZMTV_URL"
    local htm  singer song resul tmp  resultlist
    tmp=/dev/shm/tmp
 
    if [[ -z "$1" ]];then
	return   1
    fi
   
   
    singer=${1%%-*}
    song=${1#*-}
    htm="http://lrc.bzmtv.com/so.asp?key=${song}&go=go&y=1"   #go=(go|so) go为精确搜索 so模糊搜索  y=(1|2|3)(歌名|歌手|专辑) 

#    htm=`echo $htm |iconv -c -t gbk`

    resultlist=`curl "${htm}"  |grep -A 5 "class=\"acll\".*${singer}" |grep -o "lrc_db.*lrc"`

    if [[ -z $resultlist ]];then
	NoFileLrc=yes
	return  1 
    fi

    for result in ${resultlist}
    do
	result="http://lrc.bzmtv.com/"${result}
#	echo $result
	curl "${result}" -o ${tmp}
	iconv -c -f gbk -t utf-8 ${tmp} -o ${Lrcdir}/${1}.lrc	
	[[ `CHECK_LRC ${tmp} "$1" "$2"` == "LRC-RIGHT" ]] && break
    done


}

function Is_English_song ()
{
    #参数为 歌曲名
    local song
    song=$1
    echo `echo "$1" |tr '+' ' '|tr '["alnum"]' ' '`


}

function LRC123_URL()
{              
    #www.lrc123.com 搜索歌词下载
    # 一个参数， $1 歌手歌曲名
    #找不到的时候 将NoFileLrc标记yes
   
    local htm singer  song resul tmp  resultist   dstlrc  i regular_e 
    tmp=/dev/shm/tmp
 #   release=yes
    if [[ $NoFileLrc == "yes" ]] ||  [[ -z "$1" ]] ;then
	sleep 2
	return 1
    fi   
#   echo "LRC123_URL"
   
   song=`MultiSinger $1`
   singer=${song%%-*}
   singer=`echo "$singer" |tr '_' ' '`
   song=${song#*-}


   if [[ -n `Is_English_song $song` ]];then
       release=no
   fi


   htm="http://www.lrc123.com/?keyword=${song}&field=song"

   curl "${htm}" -o $tmp 



    resultist=`cat $tmp  |egrep -A 10 "歌手:.*${singer}" |\
	grep -A 1 "下载" |egrep -o "/download/lrc/[0-9]+-[0-9]+.aspx"`



    if [[ -z $resultist ]];then
	resultist=`cat $tmp |grep -A 1 "下载" |egrep -o "/download/lrc/[0-9]+-[0-9]+.aspx"`
#	echo "none resultists here"
	if [[ -z $resultist ]];then
	    BZMTV_URL "$1"
	    return  1
	fi
   fi

 #   echo "resultlist $resultist "

    dstlrc="${Lrcdir}/$1.lrc"
    file=$1

    for result in ${resultist}
    do
	result="http://www.lrc123.com"${result}
	curl "${result}" -o ${tmp}
	iconv -c -f gbk -t utf-8 ${tmp} -o "$dstlrc"	
	if [[ `CHECK_LRC "${dstlrc}" "$file" "$release"` == "LRC-RIGHT" ]];then
	     break
	else
	    rm -f "$dstlrc"
	fi
    done



}


function Compare_MultiSinger ()
{
    #参数1 为歌曲歌手的名字
   
    local song3
 
    song=`MultiSinger "$1"`

    # if [[ -n `ls -1 "$Lrcdir" |egrep "$song"` ]];then
    #     echo "true" 
    # else
    # 	echo "false"
    # fi
  
   
   echo `ls -1 "$Lrcdir" |egrep "$song"`
 
}



CHECK_AND_DOWN_LRC() {
	#检查有无歌词文件和是否正确，无则下载，不正确则重新下载
	
	#两个参数：S1 歌手歌曲名字; $2：是否执行歌词检查（yes执行，no不执行）
        #Doname完成歌曲歌手的格式化
	local lrcnm downtimes song  file
	lrcnm=1;downtimes=0

#	echo "CHECK_AND_DOWN_LRC $1"
#	song=`echo "$1" |tr ' ' '+'`
	song="$1"
	file=`Compare_MultiSinger "$song"`
	if [[ -z "$file" ]] ;then
	    {
		#对本地已有的歌词,不再进行正确性检查,避免下不到正确歌词的歌(或者歌词信息不标准的)一直试着下载             
	        NoFileLrc=no   
		DOWNLRC "$song"
		while [ "`CHECK_LRC "$Lrcdir/$song.lrc" "$song" "$2"`" = "LRC-WRONG" -a "$downtimes" -lt "${Maxdowntimes:-4}" ]; do
			DOWNLRC "$song" "$lrcnm"
		   	((lrcnm++))
		   	((downtimes++))
			DEBUGECHO "CHECK_AND_DOWN_LRC: Title => $song; Downtimes => $downtimes; Lrcnm => $lrcnm"
		done
		#如果都以上判断都失败,还是留下第一个比较保险.
		echo     "$Lrcdir/$song.lrc"  		    
	}
	else
	    echo  "$Lrcdir/$file"
	fi

}

GET_TITLE() {
	#获取当前播放的歌曲名字

	#参数两个，$1 播放器类型,默认 rhythmbox、$2 获取歌曲名字方式，默认取id3; 返回正在播放的歌曲名
	#title和artist信息应该尽量同时获取(而不是使用两个命令分别获取)
	#以免此时播放器进入下一首而造成错位 (A的标题和B的歌手名)，参见mocp, quodlibet的处理方法
	local readname t a tmp flag ans sn f

	readname=${2:-id3}

	case "${1:-rhythmbox}" in


	"amarok")
		if [ "`amarok -v|grep "Amarok:\ *2\.*"`" ];then
			tmp="`dbus-send --session --print-reply --dest=org.kde.amarok \
			/Player org.freedesktop.MediaPlayer.GetMetadata`"
			if [ "$readname" = "filename" ];then
				f="`echo "$tmp" |grep 'string "file:' \
				|perl -p -e 's/%(..)/pack("c", hex($1))/eg'|sed 's/^.*\///'`"
				DO_NAME "$f"	
			else
				t="`echo "$tmp" |grep -FA1 'string "title"'|tail -n1|awk -F'"' '{print $2}'`"
				a="`echo "$tmp" |grep -FA1 'string "artist"'|tail -n1|awk -F'"' '{print $2}'`"
				if [ "$t" ];then
					[ "$a" ] && echo -n "$a - "
					echo "$t"
				fi
			fi
		else
			#TODO amarok1不能读取id3信息？
			#amarok1我没有，不能测试，下面的代码未经过测试，谁有amarok1的请测试下。--xiooli
			if [ "$readname" = "filename" ];then
				DO_NAME "`dcop amarok player nowPlaying 2>/dev/null`"
			else
				t="`dcop amarok player title 2>/dev/null`"
				a="`dcop amarok player artist 2>/dev/null`"
				if [ "$t" ];then
					[ "$a" ] && echo -n "$a - "
					echo "$t"
				fi
			fi
		fi
	;;


	"rhythmbox")

                t="`rhythmbox-client --no-start --print-playing-format %aa-%tt 2>/dev/null`"

              
		[ "$t" = "Not playing" ] && t=""
		DO_NAME "$t"
		DEBUGECHO "GET_TITLE: $t"
	;;

	"mplayer")
	#	[ -p "/dev/shm/mfifo" -a -p "/dev/shm/ififo" -a "`ps ax|grep "mplayer"|grep "/dev/shm/mfifo"`" ] || exit
		echo "get_file_name">/dev/shm/mfifo
		t="`cat "/dev/shm/ififo"|awk -F\' '/^ANS_FILENAME/{gsub(/\.mp3|\.wma|\.MP3|\.WMA/,"",$2);print $2}'`"
		[ "$t" ] && echo "$t"
		DEBUGECHO "GET_TITLE: $a - $t"
	;;
	esac
}   

GET_PLTM() {
   #参数一个，$1，播放器类型；返回播放器播放歌曲的当前时间 (转换成秒)

   #GET_PLTM如果是空那么就表示已经停止或者播放器退出。
   
	local tm min sec tmptm
	if [ "$Dismode" = "fifo" -a -p "/dev/shm/lrcfifo" ];then
		echo "write to fifo"
		echo -n "">/dev/shm/lrcfifo #防止读lrcfifo时等待 amoblin 4.14 9:49
	fi

	case "${1:-rhythmbox}" in


	"amarok")
		if [ "`amarok -v|grep "Amarok:\ *2\.*"`" ];then
			tm="`dbus-send --session --print-reply --dest=org.kde.amarok \
			/Player org.freedesktop.MediaPlayer.PositionGet 2>/dev/null \
			|grep int32|awk '{print int($2/1000)}'`"
		else
			tm="`dcop amarok player trackCurrentTime`"
		fi
			[ "$tm" ] && echo "$tm"
	;;

	"rhythmbox")
		tm="`dbus-send --session --print-reply --dest=org.gnome.Rhythmbox \
		/org/gnome/Rhythmbox/Player org.gnome.Rhythmbox.Player.getElapsed 2>/dev/null \
		|tail -n 1|awk '{print $2}'`"
		[ "$tm" ] && echo "$tm"
	;;

	"mplayer")
		[ -p "/dev/shm/mfifo" -a -p "/dev/shm/ififo" -a "`ps ax|grep "mplayer"|grep "/dev/shm/mfifo"`" ] || exit
		echo "get_time_pos">/dev/shm/mfifo
		tm="`cat "/dev/shm/ififo"|sed 's/^.*=//;s/\..*$//'`"
		[ "${tm//-/}" = "$tm" -a "$tm" != "0" ] && echo "$tm"
	;;
	esac

}

DIS_WORDS() {
	#以各种预设的模式显示文字

	#两个参数; $1:T标题W普通歌词E错误信息  正确执行按照Dismode将 $2 格式化输出

	local line i j nl

	line="$2"
	nl="\n "
	#[ "$2" = "" ] && exit

	if [ "$Dismode" = "osd" -a "`which gnome-osd-client 2>/dev/null`" ];then
		line="`echo "$line" | sed "s/&/\&amp;/g" | sed "s/</\&lt;/g" | sed "s/>/\&gt;/g"`"
		[ "$1" = T ] && line="****** $line ******"
		[ "$1" = E ] && line="错误: $line"
		if [ "$Addnewline" = "on" ];then
			if [ "$Vposition" = "top" ];then
				line="$nl$line"
			else
				line="$line$nl"
			fi
		fi
		#NOTE: 下面的echo是需要的，不然\n会直接显示出来。
		gnome-osd-client -f "<message id='lrcdis' osd_fake_translucent_bg='off' osd_vposition='$Vposition' osd_halignment='$Halignment' animations='$Animations' hide_timeout='$Timeout'><span size='$Size' foreground='$Foreground'>`echo -ne "$line"`</span></message>"
	elif [ "$Dismode" = "fifo" -a -p "/dev/shm/lrcfifo" ];then
		[ "$1" = T ] && line="****** $line ******"
		[ "$1" = E ] && line="错误: $line"
		echo -ne "$line" > /dev/shm/lrcfifo
	elif [ "$Dismode" = "notify" -a "`which notify-send 2>/dev/null`" ];then
		[ "$2" = "" ] && return
		[ "$1" = T ] && line="****** $line ******"
		[ "$1" = E ] && line="错误: $line"
		if [ -n "$NotifyIcon" ];then
			notify-send -c lrcdis -i "$NotifyIcon" -t "$Timeout" -- "$line"
		else
			notify-send -c lrcdis -t "$Timeout" -- "$line"
		fi
	elif [ "$Dismode" = "title" ];then
		[ "$1" = T ] && line="****** $line ******"
		[ "$1" = E ] && line="错误: $line"
		echo -ne "\e]0;$line\a"
	elif [ "$Dismode" = "cli" ];then
		if [ "$1" = W ] ;then
			N="${N:-1}"
			[ "$N" = "1" ] && \
			for i in `seq $Row`;do
				tput cup "$i" 0
				echo -ne "\e[K"
			done
			tput cup "$N" 0
			echo -ne "\033[${!Lyricscolor}m "${line}"\033[0m"
			if [ "$N" -lt "$Row" ];then
				((N="$N"+1))
			else
				((N=1))
			fi
		else
			if [ "$1" = T ] ;then
				line="****** $line ******"
			else
				line="错误: $line"
			fi
			for i in `seq 0 $Row`;do
				tput cup "$i" 0
				echo -ne "\e[K"
			done
			tput cup 0 0
			echo -ne "\033[${!Titlecolor}m ${line}\033[0m"
			((N=1))
		fi
	elif [ "$Dismode" = "kdialog" ];then
		[ "$1" = T ] && line="****** $line ******"
		[ "$1" = E ] && line="错误: $line"
		kdialog --passivepopup "$line" "${Ktimeout:-5}" &>/dev/null

	else
		[ "$1" = T ] && line="****** $line ******"
		[ "$1" = E ] && line="错误: $line"
		echo "$line"
	fi
}

DISPLAY() {
	#显示歌词函数

	#参数两个，$1： 时间、$2： 字符串；正确执行将字符串格式化输出

	local line tmp

	if [ "$1" ] && [ "$2" ];then
		DEBUGECHO "DISPLAY: x$1x"
		#下面这一长句之所以这么繁复是为了让歌词文件里面本来是几行，但是因为某种原因合并到一行的烂文件还能正确显示
		#例如：[02:19.46][00:59.86]XXXXXX[03:26.38][02:27.68][01:08.23]YYYYYY
		#亦即即使你把歌曲文件连成只有一行也能正确显示而不会掉词。
		[ "$Tm" != "$1" ] && Tm="$1" && { #只显示与上次时间不一样的
			tmp="`echo "$2"|grep -F "$1.
$1]"`" && [ "$tmp" ] && { #找到该行
				DIS_WORDS W "`echo "$tmp"|awk -F"$1" \
				'{gsub(/(\[[0-9]*:[0-9]*\.[0-9]*\])|(^\.[0-9]*\])|(\[[0-9]*:[0-9]*\])|(^\])/,"\n",$2);
				print $2}' | grep -v '^$' | head -n 1`"
			} 
		}
	fi
}






GET_STAT() {
	#获取播放器的当前状态

	#没有参数，有两个独立的时间循环，一个用于产生时间信号（独立于播放器的时间信号，一定循环次数以后与播放器的时间相校准，
	#这样减少了获取播放器时间的次数，减少 cpu 开支，但是在有拖动时可能反应有一点延迟）；另一个用于间隔一定时间后获取标题
	#Every line is made up of two parts: 
	#  Title This Song is Great
	#  Time 01:23
	#  Error 播放器已停止或未开启
	#返回的时间为秒
	local pltm0 pltm1 n0 n1 title

	Sleeptm=${Sleeptm:-0.6}

	while :;do
		((n0=${n0:-0}+1)); [ "$n0" -gt 5 ] && n0=1
		((n1=${n1:-0}+1)); [ "$n1" -gt 6 ] && n1=1
		sleep $Sleeptm
		[ "$n0" = "1" ] && {
			PLAYER_RUNNING "$Player" || { echo "Error 未发现被支持的播放器进程！" && exit; }
		}
		[ "$n0" = "1" ] && { 
			pltm0="`GET_PLTM $Player`"
			if [ "${pltm0:-0}" = "$pltm1" ] || [ "$pltm0" = "" ];then
				echo "Error 播放器已停止或未开启！"
				PlayerStat="Unknow"
				sleep 3
				continue
			else
				pltm1="$pltm0"
			fi 
		}
		
		[ "$n1" = "1" ] && {
		       
			title="`GET_TITLE $Player $Readname`"                            
		
			if [ "$title" ];then
				echo "Title $title"
				PlayerStat="Playing"
			else
				PlayerStat="Unknow"
			fi
		}
		[ "$PlayerStat" = "Playing" ] && echo "Time $(eval "echo|awk '{print "${pltm0:-0}+$Sleeptm*$n0"}'")"
	done
}

HANDLE_PARAM() {
	#读取并处理命令行参数

	#参数 $@ 从命令行传入。

	local param arg title conf_file editor
	while getopts hvC:d:s:p:m:onfkcteDE param ; do
		case "$param" in
			h|--help)USAGE;exit 0;;
			v|--version)echo $0" Version:"$Version;exit 0;;
			C|--config) Conf_file="$OPTARG";INIT;;
			d|--download)
				title="$OPTARG"
				if [ "$title" ];then
					CHECK_AND_DOWN_LRC "$title" "$Checklrc"
					if [ -f "$Lrcdir/$title.lrc" ];then
						echo "歌词文件 $Lrcdir/$title.lrc 已保存。"
					else
						echo "$title 的歌词文件下载失败：（"
					fi
				else
					echo "未输入歌曲名！"
				fi
				exit 0;;
			E|--edit-conf) 
				echo "输入欲编辑的配置文件，回车编辑默认文件：$Conf_file"
				read -p "请输入：" conf_file
				echo "输入编辑器，默认 vim"
				read -p "请输入：" editor
				eval "${editor:-vim} \"${conf_file:-$Conf_file}\""
				exit 0
			;;
			s|--save-dir) Lrcdir="$OPTARG";;
			p|--player) Player="$OPTARG";;
			m|--mode) Dismode="$OPTARG";;
			o|--osd) Dismode=osd;;
			n|-notify) Dismode=notify;;
			f|--fifo) Dismode=fifo;;
			c|--cli) Dismode=cli;;
			t|--title) Dismode=title;;
			k|--kdialog) Dismode=kdialog;;
			e|--echo) Dismode=echo;;
			D|--debug) Debug=1;;
			#-*)gettext "No such option: ">&2; echo "$param"; exit 1;;
			"")
			  	gettext "Parameter not used: " 1>&2
			  	echo "$arg" 1>&2
			  ;;
		  esac
	done
	shift $((OPTIND-1))

	[ "$Dismode" = "fifo" ] && { [ -p /dev/shm/lrcfifo ] || mkfifo /dev/shm/lrcfifo; }

}

#===========================主流程==============================

INIT
HANDLE_PARAM $@
case "$Dismode" in
	osd|notify|fifo|title|cli|kdialog|echo)
	sed -i "s/^Dismode=.*/Dismode=$Dismode/" "$Conf_file"
	;;
	*)
	echo "无效的显示模式: $Dismode ,用默认的 echo 模式替代." >&2
	;;
esac
Player="`CHECK_PLAYER`"
if [ "$Player" ];then
	sed -i "s/^Player=.*/Player=$Player/" "$Conf_file"
else
	DIS_WORDS E "未发现被支持的播放器进程！"
	[ "$Dismode" = cli ] && echo
	exit
fi
Title="`GET_TITLE $Player $Readname`"
pltm="`GET_PLTM $Player`"
#TODO 目前PlayerStat的值只有Unknow和Playing,以后可以考虑加入Pause等状态.
PlayerStat="Unknow"

GET_STAT |while read line0;do
	[ "$Dismode" = "fifo" -a -p "/dev/shm/lrcfifo" ] && echo -n "">/dev/shm/lrcfifo #防止读lrcfifo时等待 amoblin 4.14 9:49 #非fifo模式不必要生成该文件 bones7456
	DEBUGECHO "GET_STAT: $line0<<"
	[ "$line0" = "$line1" ] && continue
	line1="$line0"
	read op arg <<< "$line0"
	[ "$op" = "Error" ] && DIS_WORDS E "$arg"
	[ "$op" = "Title" ] && [ "$title" != "$arg" ] && [ "$arg" != "" ] && {
	  
	    title="$arg"
		Title="$arg"


		DIS_WORDS T "$Title";
	
		lrcfile=`CHECK_AND_DOWN_LRC "$Title" "$Checklrc" ` 
	
		if [ -f "$lrcfile" ]; then
			lrc="$(< "$lrcfile")"
		else
			lrc=""
			DIS_WORDS E "未能下载歌词"
		fi
	
		#处理 offset
		offset="`echo $lrc|awk -F"offset:" '{gsub("].*$","",$2);print $2/1000}'`";offset=${offset:-0}
	}
	[ "$op" = "Time" ] && [ "$lrc" ] && {
		arg="${arg:-0}";
		arg="`eval "echo|awk '{print "$arg+$offset"}'"`"
		arg=${arg//.*/};arg=${arg:-0}
		arg="`printf '%.2d:%.2d' $(($arg/60)) $(($arg%60))`"
 		DISPLAY "$arg" "$lrc"
	}
done
