set cfg_port "29005"
set cfg_passwd ""

proc accept {idx} {
    control $idx incoming
}

proc incoming {idx args} {
    global cfg_passwd

    set line [join $args]
    set parameters [split $line]
    set passwd [lindex $parameters 0]
    set chan [lindex $parameters 1]
    set msg [join [lrange $parameters 2 end]]
    if {$passwd == $cfg_passwd && $chan != "" && $msg != ""} {
	putserv "PRIVMSG $chan :$msg"
    }
    killdcc $idx
}

set port [listen $cfg_port script accept pub]
