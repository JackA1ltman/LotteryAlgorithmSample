<?php
/*
 * 本随机数计算法则仅供思路参考
 * 我会尽可能详细的写注释
 * 依然记住一点：计算机不存在真正意义上的随机，所谓的mt_rand/rand是由一段公式根据例如时间戳进行计算得出的值，其中rand不确定性更大但性能很低，mt_rand种子数更多，性能相比rand超出4倍有余
 * 而真正的随机则为不可控因素产生的值，例如通过大气噪音产生的值，但这对生产环境而言根本没意义*/
//隔离上面的内容
/*
 * 首先获取该玩家的皮肤数量，检测到其数量越大，则适当对指定玩家的抽取概率降低
 * 我们这里设某数组为某玩家的皮肤库，并计算其皮肤数量*/
$theUnluckyGuy=array("10010","2002","30020","1002","40050");
$theGuyNum=count($theUnluckyGuy);
//echo $theGuyNum;
/*
 * 我们假设有个倒霉蛋，array内为他拥有的皮肤代码，通过count计算出皮肤种类个数*/
$LotteryNum=3;
//$realLotteryNum=$LotteryNum + 1;
if(intval($LotteryNum / 5) >= 1 && $realLotteryNum != 4){
    $realLotteryNum=0;
}
//这里为抽奖者所抽取的次数，real为后台根据情况修改的实际抽取次数，每当抽取到4也就是实际5次的时候的下一次实际抽取次数将会重置为0
$thankYouTake="谢谢惠顾！";
$timeStamp=time();//获取时间戳
//请忽略thankYouTake变量，因为这个仅仅只是为了代表没中奖
if(!$theGuyNum==0){
    switch ($theGuyNum){
        case ($theGuyNum = 0):
            $needNum=substr($timeStamp,-1,1); //通过当前时间戳最后一位决定可能获得的皮肤区间
            if(!$realLotteryNum==4){ // 真实抽奖次数不足4次时，若此时时间戳最后一位为1或2，则可以获得皮肤
                if($needNum==1){
                    echo "你得到了XX皮肤！";//请注意，这里我计划为所有符合条件的参与者获取同一款指定皮肤
                    $realLotteryNum=0; // 重置真实抽奖次数
                }elseif($needNum>=2){
                    $highRand=mt_rand(10,20);
                    echo $highRand."你获得了XX皮肤！";//如果得数确实超过1，则在具有吸引力皮肤中使用mt_rand函数选择一个
                    $realLotteryNum=0; // 重置真实抽奖次数
                }else{
                    echo $thankYouTake;
                }
            }elseif($realLotteryNum==4){ // 真实抽奖次数为4次时，若此时时间戳最后一位为1或2，则可以获得皮肤
                $highRand=mt_rand(5,30);
                echo $highRand."你获得了XX皮肤！";
            }
            break;
            /*
             * 以上判断用于当皮肤数过少，例如只有1个或者0个的情况下，我会尽可能给予宽容度
             * 但不管得数为几，其获得的皮肤也都应该仅仅停留在甜品级
             * 只不过得到数为设定固定值时，就真的是倒霉蛋了
             * 即便没有足够的皮肤数，但保底仍应存在*/
        case ($theGuyNum => 9):
            $needNum=substr($timeStamp,8,2);
            if(!$realLotteryNum==4){
                $realNum=intval($needNum / $theGuyNum / 2); // 概率为：当前时间戳取指定位数数值除以皮肤数除以2，2的目的是保证可以出现符合的realNum数值，经测试除以2后实际出货率依然很低
                if($realNum >= 1){
                    echo $realNum."恭喜获得XX皮肤！";
                }elseif($realNum == 0){
                    echo $thankYouTake;
                }
            }else{
                $highRand=mt_rand(5,30);
                echo $highRand."你获得了XX皮肤！";
            }
            break;
            /*
             * 当皮肤数达到9个或更高的时候，我将通过一个简单公式进行计算，取整后决定
             * ①这里需要强调的是，因为存在5次保底的情况，所以随机出货或谢谢惠顾并不适用变量realLotteryNum为4的情况*/
        case ($theGuyNum => 19):
            $needNum=substr($timeStamp,7,3);
            if(!$realLotteryNum==4){
                $realNum=intval($needNum / $theGuyNum / 3) / 60; // 概率为：当前时间戳取指定位数数值除以皮肤数除以3，3的目的是保证可以出现符合的realNum数值，经测试除以3后实际出货率依然很低
                if($realNum >= 1){
                    echo $realNum."恭喜获得XX皮肤！";
                }elseif($realNum == 0){
                    echo $thankYouTake;
                }
            }else{
                $highRand=mt_rand(5,30);
                echo $highRand."你获得了XX皮肤！";
            }
            /*
             * 当皮肤数达到19个或更高的时候，利用上式的变式，取整后决定
             * 其中你会发现，取证值为1的情况将会降低很多，也因此你抽奖抽中的概率和你库存皮肤数息息相关
             * 此项与上部分相同①*/
    }
}
/* 现阶段我选择将第一轮抽奖时间戳倒置取指定位数数值，其他轮不倒置
 * 我的核心是围绕时间戳进行数值计算，其皮肤数值位数与时间戳位数取值息息相关，但并不是例如我有19个皮肤就一定只能选择2位时间戳，这个要看实际情况自行决定
 * 如果某一个人的皮肤数膨胀到超过时间戳的最大位数，则固定取时间戳最大位数进行计算，不过
 * 真的有人能弄到这么多皮肤么？而且既然都弄到这么多皮肤了，抽奖对他来说还有什么意义呢？*/

//以上仅为思路参考，切勿直接搬运或直接用于生产环境中
//--JackAltman