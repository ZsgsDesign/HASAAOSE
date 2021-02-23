<?php

/* 这是Laravel的启动脚本，如果这里能够用时间众筹术省时间的话那我们的系统就天下第一了。
ZZZZZZZZZZZZZZ$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
ZZZZZZZ$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
$Z$$$$$$$$$$$$$$$$$$$$$$$$$$$$O8NNNNMNMNNNNNNNNZ$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
$$$$$$$$$$$$$$$$$$$$$$$$$$$DNNNNNNNNNNNNNNNDDNNNNNNO$$$$$$$$$$$$$$$$$$$$$$$$$$$$
$$$$$$$$$$$$$$$$$$$$$$$$ONNNNNDNNNDDDDDDDDDD8DDD88NNDNZ7777777777$$77$$7$$$$$$$7
$$$$$$$$$$$$$$$$$77777ONNNNNDDDDDD8DD88O8888O88888D8DDDNO77777777777777777777777
$$$$$$$$$$$$$$7777777NNNNNNNDD8D8D8OOZZZZOZZ$ZZZOO88OOO8ND7777777777777777777777
$$$$$$77777777777777NNNNNDDNDD88OZOZZ77II777II7$$$$ZZ888O8DZ77777777777777777777
7777777777777777777NMNND8O888OZ$$$777III???????????7$$Z8D8DN87777777777777777777
777777777777777777DMMNN88OOZZZ$7$$777IIII???????????II7$88ODN$777777777777777777
77777777777777777$NMNND88ZZZZ$$7777IIII??????+++??????I7Z8O8DNIIIIIIIIIII7I7777I
7777777777777777INMMMNDOOZOOZ$$$77IIIII??+?+++++++++?I?I$ZODNNOIIIIIIIIIIIIIIIII
777777777IIIIIIIDMMMNNDD8ZZOZZ$$77I7IIII?????+=+++++???I7$ODNNNIIIIIIIIIIIIIIIII
77IIIIIIIIIIIIIIMMMMMNN8OZ8OOZ$7777IIII????+?+?++++++?I?I$ODNMNIIIIIIIIIIIIIIIII
IIIIIIIIIIIIIIIIMMMMMNNOOO88OZ$7777II???????????++++??III$O8NNDIIIIIIIIIIIIIIIII
IIIIIIIIIIIIIIIIMMMMMNDOO88OOZ$$77II??+???+?I?++++++??I??ZODNMZ????I????IIIIIIII
IIIIIIIIIIIIIIIIMMMMNMND88888OZZZ$$$7I+?I?+III???????III7$ODMM????????????????I?
IIIIIIII????????IMMMMNDD888NMDOZ$7$NN8II7??77I7$OO$II7III$ONMN??????????????????
II???????????????8MMMMMMNDMOOOZZZ$7I??7N$??NZ$$$7I?+?IIMN$DNNO??????????????????
?????????????????8DNNNDNNM88888ONONN8$7$MMMOOOODN8I7??IIMDDN8???????????????????
?????????????????O88DD888MO8OOOO$7I?O8O8MMMI$OOZZ7I$ZIIIZN$OO+++++++++++????????
???????????++++++88OO888OMZZZZ$$$$77$O8M$?7DI$77?++?III?$O7I7+++++++++++++++++++
?????++++++++++++78O888888O$$$7I$$777ON87?+M$7$$$7II????7I?II+++++++++++++++++++
++++++++++++++++++8O88888OMOZ$77IIIIZN8ZI?+?M77III??????NII7++++++++++++++++++++
++++++++++++++++++8O8888888O8M8$7$DNND8ZI?~??8I7$77??+$$III+++++++++++==++++++++
+++++++++++++++++=I8O888888O$7II??I8OZ88$7?I??DOO8OO$I??7??+====================
+++++++============OO8888888Z$777$888DMD8OOMNO++77I7II?II??=====================
===================OO88888888OZZZZ$ZZOOOZ??++++++IZ7777II++=====================
=====================88888888O8OOOZ$$I7I???+++??I77$7IIII+======================
=====================O888888Z$8O88OZ$$7I??+++??II7III?III~~~=~~~~~~~=~=~=======~
====================~O888888O$78DNDNDD8ZOZZ$$ZOZ$7IIIIIII~~~~~~~~~~~~~~~~~~~~~~~
==========~~~~~~~~~~~OO888888Z7Z88888Z7I?II??I?+?IIIIII7~~~~~~~~~~~~~~~~~~~~~~~~
=~~=~~~~~~~~~~~~~~~~~~88888888$$OZZZZ$77777I++++?II7II77~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~888888DD8$7OOZ$$$$777II????I77777:~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~:~NM8888DDD88$$Z$77III?+++????I7777:~::::::::::::::::::~~~~~~
~~~~~~~~~~~~~~::~:::MMM8D88DDDDD8ZZZ$777II??????I$$$I:::::::::::::::::::::::::::
~~~~~~~::::::::::::MMMMM8ZD8DDDDDD8OOO$$$777III$$ZZI::::::::::::::::::::::::::::
::::::::::::::=NMMMMMMMMMM7NDD8DDDDDDD8OOOOZOOZ$$$$I::::::::::::::::::::::::::::
::::::::::::NMMMMMMMMMMMMMM77D88DDDDD88DDDDDOZZZ$ZIMNDNO,::,,,,,,,,,,,,,::::::::
::::::::,?NNMMMMMMMMMMMMMMMM77I788D8O8888OOZ$Z$$77:NNNNNNNNN+,,,,,,,,,,,,,,,,,,,
:::::::MNNNNNMMMMMMMMMMMMMMMM77III7888OO8OZZ$7II.,:NNNNNNNNNNNND~,,,,,,,,,,,,,,,
::,NNNNNNNNNNNMMMMMMMMMMMMMMMM$IIII?I7OZ$7??++,..,:DNNNNNNNNNNNNNNNN+,,,,,,,,,,,
NNNNNNNNNNNNNNNNMMMMMMMMMMMMMMNZI?=~,...,7D7=....,:NNNNNNNNNNNNNNNNNNNND:,,,,,,,
NNNNNNNNNNNNNNNNNMMMNMMMMMMMMMNNI===:,,O$OIO88O.,::NNNNNNNNNNNNNNNNNNNNNNNNO,.,,
NNNNNNNNNNNNNNNNNMMMMNNNNMNMMNNNN=~~~NN8DZOO$$ND,,,DNNNNNNNNNNNNNNNNNNNNNNNNNN,.
NNNNNNNNNNNNNNNNNNMMNNNNNNNNNNNNNN=:DDONNNOZ8D8ZO,,8NNNNNNNNNNNNNNNNNNNNNNNNNNND
NNNNNNNNNNNNNNNNNNMMMNNNNNNMMNNNNNN,7OZNDNN8OD+ZZ$,DNNNNNNNNNNNNNNNNNNNNNNNNNNND
NNNNNNNNNNNNNNNNNNMMNNMNNNNNNNNNNNNN,Z7I8N8N88::,.$7NNNNNNNNNNNNNNNNNNNNNNNNNNND
NNNNNNNNNNNNNNNNNMMMNNNNNNNNNNNNNNNNN~O$INNNZO,,,,:$NNNNNNNNNNNNNNNNNNNNNNNNNNND
NNNNNNNNNNNNNNNNNMMMNNNNNNNNNNNNNNNNNO~8$NDN8O8,,,,ZNNNNNNNNNNNNNNNNNNNNNNNNNNND
NNNNNNNNNNNNNNNNNNMMMMMMMMMNNNNNNNNNNN~$NDN888O.:::Z8DNNNNNNNNNNNNNNNNNNNNNNNNND
什么？系统又炸了？完蛋了，系统的运行时间被反向众筹走了，赶紧把这个注释删除保平安吧。 */


/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
