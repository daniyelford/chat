<template>
    <div class="wrapper">
        <div class="candles">
            <div class="light__wave"></div>
            <div class="candle1">
                <div class="candle1__body">
                    <div class="candle1__eyes">
                        <span class="candle1__eyes-one"></span>
                        <span class="candle1__eyes-two"></span>
                    </div>
                    <div class="candle1__mouth"></div>
                </div>
                <div class="candle1__stick"></div>
            </div>
            <div class="candle2">
                <div class="candle2__body">
                    <div class="candle2__eyes">
                    <div class="candle2__eyes-one"></div>
                    <div class="candle2__eyes-two"></div>
                </div>
            </div>
            <div class="candle2__stick"></div>
            </div>
            <div class="candle2__fire"></div>
            <div class="sparkles-one"></div>
            <div class="sparkles-two"></div>
            <div class="candle__smoke-one"></div>
            <div class="candle__smoke-two"></div>
        </div>
        <div class="floor"></div>
    </div>
</template>
<style lang="scss" scoped>
// colors
$lightsOnBg: #FEF4AD;
$lightsOutBg: #F8AE39;
$candleColor:#FFFFFD;
$candleShadow:#673C63;
$floorColor:#AD9598;
$fireColor:#FF9800;
$fireColor2:#FFC107;
$fireShadow: #E7C980;
$angerColor:#F44336;
$primaryColor: #FEF4AD;
$secondaryColor:rgb(0,0,0);
$candleInsetShadow:#95c6f2;
$smokeColor:grey;
//sizes
$floorWidth: 350px;
$floorHeight:5px;
$candleWidth:35px;
$candleHeight:100px;
$stickWidth:3px;
$stickHeight:15px;

body
{
  background-color:$lightsOnBg;
  animation:change-background 3s infinite linear;
}
.wrapper
{
   position:absolute;
  left:50%;
  top:70%;
  transform:scale(1.5,1.5) translate(-50%,-50%);
}
.floor
{
  position:absolute;
  left:50%;
  top:50%;
  width:$floorWidth;
  height:$floorHeight;
  background:$candleShadow;
  transform:translate(-50%,-50%);
  box-shadow:0px 2px 5px #111;
  z-index:2;
}
.candles
{
  position:absolute;
  left:50%;
  top:50%;
  width:250px;
  height:150px;
  // background:$secondaryColor;
  // opacity:0.8;
  transform:translate(-50%,-100%);
  z-index:1;
}
.candle1
{
  position:absolute;
  left:50%;
  top:50%;
  width:$candleWidth;
  height:$candleHeight;
  background:#fff;
  border:3px solid $candleShadow;
  border-bottom:0px;
  border-radius:3px;
  transform-origin:center right;
  transform:translate(60%,-25%);
  box-shadow: -2px 0px 0px $candleInsetShadow inset;
   animation:expand-body 3s infinite linear;
}
.candle1__stick,.candle2__stick
{
  position:absolute;
  left:50%;
  top:0%;
  width:$stickWidth;
  height:$stickHeight;
  background:$candleShadow;
  border-radius:8px;
  transform:translate(-50%,-100%);
}
.candle2__stick
{
  height:$stickHeight*0.8;
  transform-origin: bottom center;
  animation:stick-animation 3s infinite linear;
}
.candle1__eyes,.candle2__eyes
{
  position:absolute;
  left:50%;
  top:0%;
  width:$candleWidth;
  height:30px;
  transform:translate(-50%,0%);
}
.candle1__eyes-one{
   position:absolute;
  left:30%;
  top:20%;
  width:5px;
  height:5px;
  border-radius:100%;
  background:$candleShadow;
  transform:translate(-70%,0%);
  animation:blink-eyes 3s infinite linear;
}
.candle1__eyes-two
{
   position:absolute;
  left:70%;
  top:20%;
  width:5px;
  height:5px;
  border-radius:100%;
  background:$candleShadow;
  transform:translate(-70%,0%);
   animation:blink-eyes 3s infinite linear;
}
.candle1__mouth
{
  position:absolute;
  left:40%;
  top:20%;
  width:0px;
  height:0px;
  border-radius:20px;
  background:$candleShadow;
  transform:translate(-50%,-50%);
  animation: uff 3s infinite linear;
}
.candle__smoke-one
{
  position:absolute;
  left:30%;
  top:50%;
  width:30px;
  height:3px;
  background:$smokeColor;
  transform:translate(-50%,-50%);
  animation:move-left 3s infinite linear;
}
.candle__smoke-two
{
  position:absolute;
  left:30%;
  top:40%;
  width:10px;
  height:10px;
  border-radius:10px;
  background:$smokeColor;
  transform:translate(-50%,-50%);
  animation:move-top 3s infinite linear;
}

.candle2
{
  position:absolute;
  left:20%;
  top:65%;
  width:$candleWidth*1.20;
  height:$candleHeight*0.60;
  background:#fff;
  border:3px solid $candleShadow;
  border-bottom:0px;
  border-radius:3px;
  transform:translate(60%,-15%);
  transform-origin:center right;
  box-shadow: -2px 0px 0px $candleInsetShadow inset;
  animation: shake-left 3s infinite linear;
}
.candle2__eyes-one
{
   position:absolute;
  left:30%;
  top:50%;
  width:5px;
  height:5px;
  display:inline-block;
  border:0px solid $candleShadow;
  border-radius:100%;
  float:left;
  background:$candleShadow;
  transform:translate(-80%,0%);
   animation:changeto-lower 3s infinite linear;
}
.candle2__eyes-two
{
   position:absolute;
  left:70%;
  top:50%;
  width:5px;
  height:5px;
  display:inline-block;
  border:0px solid $candleShadow;
  border-radius:100%;
  float:left;
  background:$candleShadow;
  transform:translate(-80%,0%);
  animation:changeto-greater 3s infinite linear;
  
}
.light__wave
{
  position:absolute;
  top:35%;
  left:35%;
  width:75px;
  height:75px;
  border-radius:100%;
  z-index:0;
   transform:translate(-25%,-50%) scale(2.5,2.5);
    border:2px solid rgba(255,255,255,0.2);
   animation:expand-light 3s infinite linear;
}
.candle2__fire
{
  position:absolute;
  top:50%;
  left:40%;
  display: block;
      width: 16px;
      height: 20px;
      background-color: red;
      border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
  background:$fireColor;
  transform:translate(-50%,-50%);
  animation: dance-fire 3s infinite linear;
}
//animations

//animation for blinking eyes
@keyframes blink-eyes{
  0%,35%
  {
    opacity:1;
    transform:translate(-70%,0%);
  }
  36%,39%
  {
    opacity:0;
    transform:translate(-70%,0%);
  }
  40%
  {
    opacity:1;
    transform:translate(-70%,0%);
  }
  50%,65%
  {
    transform:translate(-140%,0%);
  }
  66%
  {
    transform:translate(-70%,0%);
  }
}
@keyframes expand-body
{
  0%,40%
  {
     transform:scale(1,1) translate(60%,-25%);
  }
  45%,55%
  {
    transform:scale(1.1,1.1) translate(60%,-28%);
  }
  60%
  {
     transform:scale(0.89,0.89) translate(60%,-25%);
  }
  65%
  {
     transform:scale(1,1) translate(60%,-25%);
    
  }
  70%
  {
    transform:scale(0.95,0.95) translate(60%,-25%);
  }
  75%
  {
    transform:scale(1,1) translate(60%,-25%);
  }
  
}

@keyframes uff{
  0%,40%{
    width:0px;
    height:0px;
  }
  50%,54%
  {
    width:15px;
    height:15px;
   left:30%;
  }
  59%
  {
     width:5px;
    height:5px;
    left:20%;
  }
  62%
  {
     width:2px;
    height:2px;
    left:20%;
  }
  67%
  {
     width:0px;
    height:0px;
    left:30%;
  }
  
}

@keyframes change-background
{
  0%,59%,98%,100%{
    background:$lightsOnBg;
  }
  61%,97%
  {
    background:$lightsOutBg;
  }
 
}
@keyframes move-left{
  0%,59%,100%
  {
    width:0px;
    left:40%;
  }
  60%
  {
    width:30px;
    left:30%;
  }
  68%
  {
    width:0px;
    left:20%;
  }
}

@keyframes move-top{
  0%,64%,100%
  {
    width:0px;
    height:0px;
    top:0%;
  }
  65%
  {
    width:10px;
    height:10px;
    top:40%;
    left:40%;
  }
  80%
  {
    width:0px;
    height:0px;
    top:20%;
  }
}
@keyframes shake-left{
  0%,40%{
    left:20%;
    transform:translate(60%,-15%);
  }
  50%,54%
  {
   left:20%;
    transform:translate(60%,-15%);
  }
  59%
  {
    left:20%;
    transform:translate(60%,-15%);
  }
  62%
  {
     left:18%;
    transform:translate(60%,-15%);
  }
  65%
  {
    left:21%;
    transform:translate(60%,-15%);
  }
  67%
  {
    left:20%;
    transform:translate(60%,-15%);
  }
  75%
  {
    left:20%;
    transform:scale(1.15,0.85) translate(60%,-15%);
    background:#fff;
    border-color:$candleShadow;
     
  }
  
  // 80%
  // {
  //   background:$angerColor;
  //   alpha:0.5;
  //   border-color:$angerColor;
  // }
  
  91%
  {
    left:20%;
    transform:scale(1.18,0.82) translate(60%,-10%);
    background:$angerColor;
    border-color:$angerColor;
     box-shadow: -2px 0px 0px $angerColor inset;
  }
  92%
  {
    left:20%;
    transform:scale(0.85,1.15) translate(60%,-15%);
  }
  95%
  {
    left:20%;
    transform:scale(1.05,0.95) translate(60%,-15%);
  }
  97%
  {
    left:20%;
    transform:scale(1.0,1.0) translate(60%,-15%);
  }
  
}
@keyframes stick-animation{
   0%,40%{
   left:50%;
  top:0%;
  transform:translate(-50%,-100%);
  }
  50%,54%
  {
   left:50%;
  top:0%;
    transform:translate(-50%,-100%);
  }
  59%
  {
    left:50%;
  top:0%;
    transform:translate(-50%,-100%);
  }
  62%
  {
    left:50%;
  top:0%;
    transform:rotateZ(-15deg) translate(-50%,-100%);
  }
  65%
  {
    left:50%;
  top:0%;
    transform:rotateZ(15deg) translate(-50%,-100%);
  }
  70%
  {
    left:50%;
  top:0%;
    transform:rotateZ(-5deg) translate(-50%,-100%);
  }
  72%
  {
    left:50%;
  top:0%;
    transform:rotateZ(5deg) translate(-50%,-100%);
  }
  74%,84%
  {
    left:50%;
  top:0%;
    transform:rotateZ(0deg) translate(-50%,-100%);
  }
  85%
  {
    transform:rotateZ(180deg) translate(0%,120%);
  }
  92%
  {
     left:50%;
  top:0%;
    transform:translate(-50%,-100%);
  }
 
}
@keyframes expand-light
{
  10%,29%,59%,89%
  {
    transform:translate(-25%,-50%) scale(0,0);
    border:2px solid rgba(255,255,255,0);
  }
  90%,20%,50%
  {
    transform:translate(-25%,-50%) scale(1,1);
  }
  95%,96%,26%,27%,56%,57%
  {
     transform:translate(-25%,-50%) scale(2.0,2.0);
    border:2px solid rgba(255,255,255,0.5);
  }
  0%,28%,58%,100%
  {
     transform:translate(-25%,-50%) scale(2.5,2.5);
    border:2px solid rgba(255,255,255,0.2);
  }
  
}
@keyframes dance-fire
{
  59%,89%
  {
    left:40%;
  width:0px;
  height:0px;
  }
  90%,0%,7%,15%,23%,31%,39%,47%,55%
  {
    left:40.8%;
  width:16px;
  height:20px;
    background:$fireColor2;
  }
  94%,3%,11%,19%,27%,35%,43%,51%,58%
  {
    left:41.2%;
  width:16px;
  height:20px;
    background:$fireColor;
  }
}
@keyframes changeto-lower
{
  0%,70%,90%
  {
    padding:0px;
    display:inline-block;
  border-radius:100%;
  background:$candleShadow;
    border-width:0 0 0 0;
    border:0px solid $candleShadow;
  transform:translate(-90%,0%);
  }
  71%,89%
  {
    background:none;
    border: solid $candleShadow;
    border-radius:0px;
  border-width: 0 2px 2px 0;
  display:inline-block;
  padding: 1px;
    float:left;
    transform-origin:bottom left;
    transform: rotate(-45deg) translate(-50%,-65%);
    -webkit-transform: rotate(-45deg) translate(-50%,-65%);
  }
}

@keyframes changeto-greater
{
  0%,70%,90%
  {
  top:50%;
    padding:0px;
    display:inline-block;
  border-radius:100%;
  background:$candleShadow;
    border-width:0 0 0 0;
    border:0px solid $candleShadow;
  transform:translate(-80%,0%);
  }
  71%,89%
  {
    top:30%;
    background:none;
    border: solid $candleShadow;
    border-radius:0px;
  border-width: 0 2px 2px 0;
  display:inline-block;
  padding: 1px;
    float:left;
    transform-origin:bottom left;
    transform: rotate(135deg) translate(-80%,20%);
    -webkit-transform: rotate(135deg) translate(-80%,20%);
  }
}
</style>
<!-- 
    <template>
        <div class="screen">
            <div class="wrapper">
                <div class="clouds">
                    <div class="cloud cloud-1"></div>
                    <div class="cloud cloud-2"></div>
                    <div class="cloud cloud-4"></div>
                    <div class="sun"></div>
                    <div class="cloud cloud-3"></div>
                </div>
                <div class="clouds clouds--2">
                    <div class="cloud cloud-1"></div>
                    <div class="cloud cloud-2"></div>
                    <div class="cloud cloud-3"></div>
                    <div class="cloud cloud-4"></div>
                    <div class="cloud cloud-5"></div>
                </div>
                <div class="container">
                    <div class="flats">
                        <div class="flat">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>	
                            <div class="window"></div>	
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                    </div>
                    <div class="flat flat-2">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>	
                            <div class="window"></div>	
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                    </div>
                    <div class="flat flat-3">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>	
                            <div class="window"></div>	
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                    </div>
                    <div class="flat flat-4">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>	
                            <div class="window"></div>	
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                    </div>
                </div>
                    <div class="flats">
                    <div class="flat">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>	
                            <div class="window"></div>	
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                    </div>
                    <div class="flat flat-2">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>	
                            <div class="window"></div>	
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                    </div>
                    <div class="flat flat-3">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>	
                            <div class="window"></div>	
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                    </div>
                    <div class="flat flat-4">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>	
                            <div class="window"></div>	
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="container">
                    <div class="trees trees--3">
                        <div class="tree"></div>
                        <div class="tree tree-1"></div>
                        <div class="tree tree-2"></div>
                        <div class="tree tree-3"></div>
                        <div class="tree tree-4"></div>
                        <div class="tree tree-5"></div>
                        <div class="tree tree-6"></div>
                        <div class="tree tree-7"></div>
                    </div>
                    <div class="trees trees--3">
                        <div class="tree"></div>
                        <div class="tree tree-1"></div>
                        <div class="tree tree-2"></div>
                        <div class="tree tree-3"></div>
                        <div class="tree tree-4"></div>
                        <div class="tree tree-5"></div>
                        <div class="tree tree-6"></div>
                    </div>
                </div>	
                <div class="container">
                    <div class="hotel-wrap">
                    <div class="hotel">
                        <div class="hotel-top_shade"></div>		
                        <div class="hotel-top"></div>		
                        <div class="hotel-shade"></div>
                        <div class="hotel-body"></div>
                        <div class="hotel-windows"></div>	
                        <div class="hotel-windows hotel-windows--2"></div>	
                        <div class="hotel-windows hotel-windows--3"></div>	
                        <div class="hotel-windows hotel-windows--4"></div>	
                        <div class="hotel-windows hotel-windows--5"></div>
                                    <div class="hotel-windows hotel-windows--6"></div>	
                        <div class="hotel-windows hotel-windows--7"></div>	
                        <div class="hotel-windows hotel-windows--8"></div>	
                        <div class="hotel-windows hotel-windows--9"></div>	
                    </div>
                </div>
                    <div class="hotel-wrap">
                    <div class="hotel">
                        <div class="hotel-top_shade"></div>		
                        <div class="hotel-top"></div>		
                        <div class="hotel-shade"></div>
                        <div class="hotel-body"></div>
                        <div class="hotel-windows"></div>	
                        <div class="hotel-windows hotel-windows--2"></div>	
                        <div class="hotel-windows hotel-windows--3"></div>	
                        <div class="hotel-windows hotel-windows--4"></div>	
                        <div class="hotel-windows hotel-windows--5"></div>
                                    <div class="hotel-windows hotel-windows--6"></div>	
                        <div class="hotel-windows hotel-windows--7"></div>	
                        <div class="hotel-windows hotel-windows--8"></div>	
                        <div class="hotel-windows hotel-windows--9"></div>	
                    </div>
                </div>
                </div>
                <div class="container">
                    <div class="tower-wrap">
                    <div class="tower">
                        <div class="tower-hat"></div>
                        <div class="tower-spire_ring--u"></div>
                        <div class="tower-spire_ring--m"></div>
                        <div class="tower-spire_ring--l"></div>
                        <div class="tower-spire"></div>
                        <div class="tower-body"></div>
                        <div class="tower-circle"></div>
                        <div class="tower-shade"></div>
                        <div class="tower-center"></div>
                        <div class="tower-center_ring"></div>
                        <div class="tower-mid"></div>
                        <div class="tower-mid_shade"></div>
                        <div class="tower-ring"></div>
                        <div class="tower-lower_ring"></div>
                    </div>
                </div>
                    <div class="tower-wrap">
                    <div class="tower">
                        <div class="tower-hat"></div>
                        <div class="tower-spire_ring--u"></div>
                        <div class="tower-spire_ring--m"></div>
                        <div class="tower-spire_ring--l"></div>
                        <div class="tower-spire"></div>
                        <div class="tower-body"></div>
                        <div class="tower-circle"></div>
                        <div class="tower-shade"></div>
                        <div class="tower-center"></div>
                        <div class="tower-center_ring"></div>
                        <div class="tower-mid"></div>
                        <div class="tower-mid_shade"></div>
                        <div class="tower-ring"></div>
                        <div class="tower-lower_ring"></div>
                    </div>
                </div>
                </div>
                <div class="container">
                    <div class="rathaus-container">
                    <div class="rathaus">
                    <div class="rathaus-upper_shade"></div>
                    <div class="rathaus-body_shade"></div>
                    <div class="rathaus-shade"></div>
                        <div class="rathaus-body">
                                <div class="window"></div>
                        </div>
                        <div class="rathaus-upper">
                            <div class="gable-window"></div>
                                <div class="window"></div>
                        </div>
                        <div class="rathaus-wrap">
                            <div class="windows">
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>	
                            </div>
                            <div class="door"></div>
                            <div class="windows">
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>		
                            </div>
                        </div>
                    </div>
                </div>
                        <div class="rathaus-container">
                    <div class="rathaus">
                    <div class="rathaus-upper_shade"></div>
                    <div class="rathaus-body_shade"></div>
                    <div class="rathaus-shade"></div>
                        <div class="rathaus-body">
                                <div class="window"></div>
                        </div>
                        <div class="rathaus-upper">
                            <div class="gable-window"></div>
                                <div class="window"></div>
                        </div>
                        <div class="rathaus-wrap">
                            <div class="windows">
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>	
                            </div>
                            <div class="door"></div>
                            <div class="windows">
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>		
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="container">
                    <div class="trees trees--2">
                        <div class="tree"></div>
                        <div class="tree tree-1"></div>
                        <div class="tree tree-2"></div>
                        <div class="tree tree-3"></div>
                        <div class="tree tree-4"></div>
                    </div>
                    <div class="trees trees--2">
                        <div class="tree"></div>
                        <div class="tree tree-1"></div>
                        <div class="tree tree-2"></div>
                        <div class="tree tree-3"></div>
                        <div class="tree tree-4"></div>
                    </div>
                </div>
                <div class="container">
                    <div class="tor-wrap">
                        <div class="tor">
                        <div class="tor-chariot_shade"></div>
                        <div class="tor-chariot_outer"></div>
                        <div class="tor-chariot">
                            <div class="tor-horse"></div>
                            <div class="tor-horse"></div>
                            <div class="tor-horse"></div>
                        </div>
                        <div class="tor-roof_shade"></div>
                        <div class="tor-roof"></div>
                        <div class="tor-top_shade"></div>
                        <div class="tor-top"></div>
                        <div class="tor-center"></div>
                        <div class="tor-upper_shade"></div>
                        <div class="tor-upper"></div>
                        <div class="tor-mid"></div>
                        <div class="tor-shade"></div>
                        <div class="tor-lower"></div>
                        <div class="tor-legs">   
                            <div class="tor-leg"></div>
                            <div class="tor-leg"></div>
                            <div class="tor-leg"></div>
                            <div class="tor-leg"></div>
                            <div class="tor-leg"></div>
                            <div class="tor-leg"></div>
                        </div>
                    </div> 
                </div>
                    <div class="tor-wrap">
                    <div class="tor">
                    <div class="tor-chariot_shade"></div>
                    <div class="tor-chariot_outer"></div>
                    <div class="tor-chariot">
                        <div class="tor-horse"></div>
                        <div class="tor-horse"></div>
                        <div class="tor-horse"></div>
                    </div>
                    <div class="tor-roof_shade"></div>
                    <div class="tor-roof"></div>
                    <div class="tor-top_shade"></div>
                    <div class="tor-top"></div>
                    <div class="tor-center"></div>
                    <div class="tor-upper_shade"></div>
                    <div class="tor-upper"></div>
                    <div class="tor-mid"></div>
                    <div class="tor-shade"></div>
                    <div class="tor-lower"></div>
                    <div class="tor-legs">   
                        <div class="tor-leg"></div>
                        <div class="tor-leg"></div>
                        <div class="tor-leg"></div>
                        <div class="tor-leg"></div>
                        <div class="tor-leg"></div>
                        <div class="tor-leg"></div>
                        </div>
                    </div> 
                    </div>	
            </div>
            <div class="container">
                <div class="dom-container">
                        <div class="dom">
                    <div class="dom-shade"></div>
                    <div class="dom-shade dom-shade--l"></div>
                    <div class="dom-shade dom-shade--r"></div>
                    <div class="dom-left">
                            <div class="window"></div>
                    </div>
                    <div class="dom-dome">
                            <div class="window"></div>
                    </div>
                    <div class="dom-right">
                            <div class="window"></div>
                    </div>
                    <div class="dom-body">			
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>						
                        </div>
                        <div class="door"></div>
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>						
                        </div>
                    </div>
                </div>
                </div>
                        <div class="dom-container">
                        <div class="dom">
                    <div class="dom-shade"></div>
                    <div class="dom-shade dom-shade--l"></div>
                    <div class="dom-shade dom-shade--r"></div>
                    <div class="dom-left">
                            <div class="window"></div>
                    </div>
                    <div class="dom-dome">
                            <div class="window"></div>
                    </div>
                    <div class="dom-right">
                            <div class="window"></div>
                    </div>
                    <div class="dom-body">			
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>						
                        </div>
                        <div class="door"></div>
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>						
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="container">
                <div class="trees trees--1">
                    <div class="tree"></div>
                    <div class="tree tree-1"></div>
                    <div class="tree tree-2"></div>
                    <div class="tree tree-3"></div>
                    <div class="tree tree-4"></div>
                    <div class="tree tree-5"></div>
                </div>
                <div class="trees trees--1">
                    <div class="tree"></div>
                    <div class="tree tree-1"></div>
                    <div class="tree tree-2"></div>
                    <div class="tree tree-3"></div>
                    <div class="tree tree-4"></div>
                    <div class="tree tree-5"></div>
                </div>
                </div>
            <div class="container">
                <div class="houses">
                    <div class="shades">
                        <div class="shade shade-1"></div>
                        <div class="shade shade-2"></div>
                        <div class="shade shade-3"></div>
                        <div class="shade shade-4"></div>
                        <div class="shade shade-5"></div>
                        <div class="shade shade-1"></div>
                    </div>
                    <div class="house house-1">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                        </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-2">
                            <div class="gable">
                                <div class="gable-shade"></div>
                            </div>					
                            <div class="windows">
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                            </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-3">
                        <div class="gable">					
                                <div class="gable-shade"></div>
                                <div class="gable-window"></div>
                            </div>	
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-4">
                        <div class="gable-shade"></div>
                            <div class="gable">
                                <div class="gable-window"></div>
                            </div>					
                            <div class="windows">
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                            </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-5">
                            <div class="gable-shade"></div>
                            <div class="gable">					
                                <div class="gable-window"></div>
                            </div>	
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                        </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-1">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                        </div>
                    </div>
                    <div class="house house-6">
                        <div class="gable-shade"></div>
                        <div class="gable">
                            <div class="gable-window"></div>
                        </div>	
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                        <div class="door"></div>
                    </div>
                </div>
                <div class="houses">
                    <div class="shades">
                        <div class="shade shade-1"></div>
                        <div class="shade shade-2"></div>
                        <div class="shade shade-3"></div>
                        <div class="shade shade-4"></div>
                        <div class="shade shade-5"></div>
                        <div class="shade shade-1"></div>
                    </div>
                    <div class="house house-1">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                        </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-2">
                            <div class="gable">
                                <div class="gable-shade"></div>
                            </div>					
                            <div class="windows">
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                            </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-3">
                        <div class="gable">					
                                <div class="gable-shade"></div>
                                <div class="gable-window"></div>
                            </div>	
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-4">
                        <div class="gable-shade"></div>
                            <div class="gable">
                                <div class="gable-window"></div>
                            </div>					
                            <div class="windows">
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                                <div class="window"></div>
                            </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-5">
                            <div class="gable-shade"></div>
                            <div class="gable">					
                                <div class="gable-window"></div>
                            </div>	
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                        </div>
                        <div class="door"></div>
                    </div>
                    <div class="house house-1">
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>
                            <div class="window"></div>		
                        </div>
                    </div>
                    <div class="house house-6">
                        <div class="gable-shade"></div>
                        <div class="gable">
                            <div class="gable-window"></div>
                        </div>	
                        <div class="windows">
                            <div class="window"></div>
                            <div class="window"></div>
                        </div>
                        <div class="door"></div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        
    </template>
    <style lang="scss" scoped>
        $bg-blue: #04668C;
        $bg-light: #D2F5FF;
        $house-white: #FEFACC;
        $house-shade: #E05502;
        $tree-green: #62BF1F;
        $tree-purple: #8C0B84;
        $light-shade: #F29F05;
        $dome-blue: #9FE2FE;
        $dome-dark: #028ED2;
        @mixin center {
            position: absolute;
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
        }
    
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: $bg-blue;
            height: 100vh;
            align-items: center;
        }
    
        .clouds {
            position: absolute;
            top: 20px;
            left: 30px;
            
            .cloud {
                position: absolute;
                width: 70px;
                height: 70px;
                background-color: white;
                border-radius: 50%;
            }
            
            &--2 {
                top: 20px;
                right: 167px;
                left: unset;
            }
            
            .cloud-1 {
                animation: 1s linear infinite .2s alternate move-clouds;
            }
            
            .cloud-2 {
                width: 60px;
                height: 60px;
                top: 40px;
                left: -10px;
                animation: 1s linear infinite alternate move-clouds;
            }
            
            .cloud-3 {
                width: 80px;
                height: 80px;
                top: 30px;
                left: 30px;
                animation: 1s linear infinite .5s alternate move-clouds;
            }
            
            .cloud-4 {
                width: 80px;
                height: 80px;
                top: 0px;
                left: 50px;
            }
            
            .cloud-5 {
                width: 40px;
                height: 40px;
                left: 70px;
                top: 10px;
            }
            
            .sun {
                position: absolute;
                background-color: #EEAA02;
                width: 100px;
                height: 100px;
                top: 0px;
                left: 90px;
                border-radius: 50%;
            }
            
            &--2 .cloud-1 {
                width: 70px;
                height: 70px;
                top: 0px;
                left: 20px;
                z-index: 0;
            }
            
            &--2 .cloud-2 {
                top: 20px;
            }
            
            &--2 .cloud-3 {
                width: 60px;
                height: 60px;
                top: 40px;
                left: 20px;
                z-index: 0;
            }
            
            &--2 .cloud-4 {
                width: 70px;
                height: 70px;
                top: 20px;
                animation: 1s linear infinite .1s alternate move-clouds;
            }
            
            &--2 .cloud-5 {
                animation: 1s linear infinite .5s alternate move-clouds;
            }
        }
    
        .flats {
            position: relative;
            display: flex;
            top: 170px;
            animation: infinity-loop 4.5s infinite linear .1s both;
        }
    
        .flat {
            position: relative;
            width: 150px;
            height: 100px;
            background-color: $house-shade;
            
            &:before {
                content: "";
                position: absolute;
                top: -20px;;
                left: 20px;
                width: 130px;
                height: 20px;
                background-color: $house-shade;
                z-index: 0;
            }
            
            &:after {
                content: "";
                position: absolute;
                top: -20px;;
                left: 0px;
                width: 0;
                height: 0;
                border-top: 20px solid transparent;
                border-right: 20px solid $house-shade;
                border-bottom: 0px solid transparent;
            }
    
            .windows {
                padding: 0 25px 0 0;
            }
            
            .window {
                width: 8px;
                height: 8px;
                margin: 5px;
            }
            
            &-2 {
                margin-left: -20px;
                margin-top: 12px;
            }
            
            &-3 {
                margin-left: -30px;
            }
            
            &-4 {
                margin-top: 12px;
            }
        }
    
        .credit {
            font-family: Helvetica,Arial,sans-serif; 
            font-size: 0.8rem;
            padding: 1rem;
            color: white;
            
            a {
                color: $light-shade;
            }
            
            strong {
                font-weight: bold;
            }
        }
    
        .screen {
            background-color: black;
            border-radius: 50px;
            padding: 20px;
        }
    
        .wrapper {
            position: relative;
            background: linear-gradient(180deg, $bg-light 0%, $bg-light 50%, $light-shade 50%, $light-shade 80%, $bg-blue 80%,  $bg-blue 100%);
            width: 100%;
            height: 275px;
            border-radius: 30px;
            overflow: hidden;
        }
    
        .container {
            display: flex;
        }
    
        .hotel-wrap {
            position: relative;
            flex: 1 0 auto;
            animation: infinity-loop 4s infinite linear .1s both;
            width: 600px;
            height: 0;
        }
    
        .hotel {
            position: relative;
            bottom: 117px;
            right: 90px;
            
            &-top {
                position: absolute;
                top: -22px;
                left: 0;
                background-color: $house-white;
                width: 60px;
                height: 12px;
                z-index: 2;
                
                &:before {
                    content: "";
                    position: absolute;
                    top: 12px;
                    left: 11px;
                    background-color: black;
                    width: 42px;
                    height: 7px;
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    top: 12px;
                    right: 0px;	
                    width: 0;
                    height: 0;
                    border-top: 0px solid transparent;
            border-left: 7px solid black;
            border-bottom: 7px solid transparent;		
                }
                
                &_shade {
                    position: absolute;
                    background-color: $light-shade;
                    width: 55px;
                    height: 22px;
                    top: -43px;
                    left: 21px;			
                }
                
                &_shade:before {
                    content: "";
                    position: absolute;
                    background-color: $light-shade;
                    top: 0px;
                    left: 18px;	
                    width: 44px;
                    height: 12px;
                }
                
                &_shade:after {
                    content: "";
                    position: absolute;
                    top: 0px;
                    left: -22px;	
                    width: 0;
                    height: 0;
                    border-left: 22px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 22px solid $light-shade;		
                }
            }
            
            &-windows {
                position: absolute;
                height: 10px;
                width: 63px;
                background-color: black;
                top: 15px;
                left: -3px;
                
                &:before {
                    content: "";
                    position: absolute;
                    height: 11px;
                    width: 23px;
                    background-color: black;
                    top: -12px;
                    left: 62px;
                    transform: skewY(-45deg);
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    top: -2px;
                    left: 0;
                    width: 0;
            height: 0;
            border-top: 2px solid transparent;
            border-right: 3px solid black;
            border-bottom: 2px solid transparent;
                }
                
                &--2 {
                    margin-top: 22px;
                }
                
                &--3 {
                    margin-top: 44px;
                }
                
                &--4 {
                    margin-top: 66px;
                }
                
                &--5 {
                    margin-top: 88px;
                }
                
                &--6 {
                    margin-top: 111px;
                }
                
                &--7 {
                    margin-top: 133px;
                }
                
                &--8 {
                    margin-top: 155px;
                }
                
                &--9 {
                    margin-top: 177px;
                }
            }
            
            &-shade {
                position: absolute;
                top: -23px;
                left: 22px;
                width: 60px;
                height: 252px;
                background-color: $light-shade;
                
                &:before {
                    content: "";
                    position: absolute;
                    top: -16px;
                    right: -1px;	
                    width: 0;
                    height: 0;
                    border-top: 8px solid transparent;
            border-left: 10px solid $light-shade;
            border-bottom: 10px solid transparent;		
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    top: 0px;
                    left: -22px;	
                    width: 0;
                    height: 0;
                    border-left: 23px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 23px solid $light-shade;		
                }
            }
            
            &-body {
                position: relative;
                width: 60px;
                height: 240px;
                background-color: $house-white;
            }
    
        }
    
        .tower-wrap {
            position: relative;
            animation: infinity-loop 4s infinite linear .1s both;
            flex: 1 0 auto;
            width: 610px;
        }
    
        .tower {
            position: relative;
            width: 2px;
            height: 0;
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
            border-bottom: 270px solid $light-shade;
            left: -150px;
            bottom: 180px;
            
            &:before {
                content: "";
                position: absolute;
                width: 2px;
                height: 0;
                left: -6px;
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-bottom: 270px solid $house-white;
            }
            
            &-hat {
                @include center;
                top: -14px;
                left: -3px;
                width: 5px;
                height: 6px;
                background-color: $house-shade;
                z-index: 5;
            }
            
            &-spire {
                @include center;
                top: -14px;
                left: -6px;
                width: 2px;
                height: 0;
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-bottom: 85px solid $light-shade;
                border-radius: 10px / 3px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                z-index: 2;
                transform: skewY(-2deg);
                
                &:after {
                    content: "";
                    @include center;
                    top: 0px;
                    left: -3px;
                    width: 2px;
                    height: 0;
                    border-left: 2px solid transparent;
                    border-right: 3px solid transparent;
                    border-bottom: 85px solid $house-white;
                    border-radius: 10px / 3px;
                    border-top-left-radius: 0;
                    border-top-right-radius: 0;
                    z-index: 2;
                    transform: skewY(-2deg);
                }
                
                &_ring--l {
                    position: absolute;
                    top: 45px;
                    left: -5px;
                    width: 10px;
                    height: 8px;
                    border-radius: 15px / 3px;
                    box-shadow: 0 5px 0 2px $house-shade;
                    transform: skewY(-4deg);
                    z-index: 5;
                }
                
                &_ring--m {
                    position: absolute;
                    top: 25px;
                    left: -4px;
                    width: 8px;
                    height: 6px;
                    border-radius: 15px / 2px;
                    box-shadow: 0 5px 0 2px $house-shade;
                    transform: skewY(-4deg);
                    z-index: 5;
                }
                
                &_ring--u {
                    position: absolute;
                    top: 6px;
                    left: -3px;
                    width: 6px;
                    height: 5px;
                    border-radius: 15px / 2px;
                    box-shadow: 0 5px 0 2px $house-shade;
                    transform: skewY(-4deg);
                    z-index: 5;
                }
            }
            
            &-body {
                @include center;
                top: 140px;
                left: -6px;
                width: 13px;
                height: 16px;
                background-color: $light-shade;
                border-radius: 10px / 4px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                z-index: 2;
                transform: skewY(-2deg);
                
                &:after {
                    content: "";
                    @include center;
                    top: 0;
                    left: -2px;
                    width: 9px;		
                    height: 16px;
                    background-color: $house-white;
                    border-radius: 10px / 4px;
                    border-top-left-radius: 0;
                    border-top-right-radius: 0;
                    z-index: 2;
                    transform: skewY(-2deg);
                }
            }
            
            &-center {
                position: absolute;
                top: 74px;
                left: -9px;
                width: 18px;
                height: 22px;
                background:  linear-gradient(90deg, $dome-blue 0%, 	$dome-blue 90%, $dome-dark 90%, $dome-dark 100%);
                border-radius: 10px / 5px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                transform: skewY(-2deg);
                z-index: 2;
                
                &:after {
                    content: "";
                    position: absolute;
                    top: -11px;
                    left: 0px;
                    width: 16px;
                    height: 10px;
                    border-radius: 10px / 4px;
                    box-shadow: 0 4px 0 0 $dome-blue;
                }
                
                &_ring {
                    position: absolute;
                    top: 70px;
                    left: -9px;
                    width: 18px;
                    height: 12px;
                    border-radius: 15px / 5px;
                    box-shadow: 0 5px 0 2px $house-shade;
                    transform: skewY(-4deg);
                    z-index: 5;
                }
            
            }
            
            &-shade {
                position: absolute;
                top: 65px;
                left: -9px;
                width: 18px;
                height: 22px;
                background: $dome-dark;
                border-radius: 10px / 5px;
                transform: skewY(-2deg);
                z-index: 0;			
            }
            
            &-mid {
                position: absolute;
                top: 158px;
                left: -10px;
                width: 22px;
                height: 10px;
                background:  linear-gradient(90deg, $dome-blue 0%, 	$dome-blue 90%, $dome-dark 90%, $dome-dark 100%);
                border-radius: 10px / 5px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                transform: skewY(-2deg);
                z-index: 2;
                
                &:after {
                    content: "";
                    position: absolute;
                    top: -10px;
                    left: 0px;
                    width: 20px;
                    height: 10px;
                    border-radius: 10px / 4px;
                    box-shadow: 0 4px 0 0 $dome-blue;
                }	
                
                &_shade {
                    position: absolute;
                    top: 150px;
                    left: -10px;
                    width: 22px;
                    height: 15px;
                    background: $dome-dark;
                    border-radius: 10px / 5px;
                    transform: skewY(-2deg);
                    z-index: 0;			
                }
            }
            
            &-ring {
                position: absolute;
                top: 93px;
                left: -25px;
                width: 50px;
                height: 25px;
                border-radius: 50%/ 30%;
                box-shadow: 0 10px 0 0 black;
                transform: skewY(-4deg);
                z-index: 2;
            }
            
            &-circle {
                position: absolute;
                top: 91px;
                left: -25px;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background-color: $dome-dark;
                z-index: 2;
                
                &:before {
                    content: "";
                    position: absolute;
                    top: 12px;
                    left: 3px;
                    width: 35px;
                    height: 35px;
                    border-radius: 50%;
                    background-color: $dome-blue;
                }
            }
            
            &-lower_ring {
                position: absolute;
                top: 168px;
                left: -6px;
                width: 14px;
                height: 10px;
                border-radius: 15px / 5px;
                box-shadow: 0 5px 0 2px $house-shade;
                transform: skewY(-4deg);
                z-index: 5;
            }
        }
    
        .rathaus-container {
            position: relative;
            bottom: 230px;
            left: 20px;
            width: 600px;
            flex: 1 0 auto;
            animation: infinity-loop 4s infinite linear .1s both;
        }
    
        .rathaus {
            position: relative;
            width: 200px;
            height: 50px;
            transform: scale(1.1);
            
            &:before, &:after {
                content: "";
                position: absolute;
                width: 14px;
                height: 14px;
                background-color: $house-white;
                top: -6px;
            }
            
            &:before {
                left: -6px;
            }
    
            &:after {
                right: -6px;
            }
            
            &-shade {
                position: absolute;
                left: 15px;
                bottom: 0px;
                width: 200px;
                height: 65px;
                background-color: $light-shade;
                
                &:after {
                    content: "";
                    position: absolute;
                    width: 14px;
                    height: 14px;
                    background-color: $light-shade;
                    top: -6px;
                    right: -7px;
                }
                
                &:before {
                    content: "";
                    position: absolute;
                    width: 20px;
                    height: 18px;
                    background-color: $light-shade;
                    top: -2px;
                    right: -4px;
                    transform: rotate(45deg);
                }
            }
            
            &-wrap {
                position: relative;
                display: flex;
                align-items: flex-end;
                height: 100%;
                padding: 0 8px;
                background-color: $house-white;
            }
            
            &-wrap .windows {
                padding: 8px 0;
            }
            
            &-wrap .window {
                margin: 5px 6px;
                width: 7px;
            }
            
            &-wrap .door {
                width: 14px;
                height: 32px;
            }
            
            &-wrap .door:after {
                content: none;
            }
            
            &-body {
                @include center;
                bottom: 45px;
                width: 45px;
                height: 30px;
                background-color: $house-white;
                
                &:before {
                    content: "";
                    position: absolute;
                    width: 0;
                    height: 0;
                    top: -7px;
                    left: 0px;
                    border-top: 7px solid transparent;
                    border-right: 8px solid #F29F05;
                    border-bottom: 0px solid transparent;
                }
                
            }
            
            &-body_shade {
                position: absolute;
                right: 63px;
                bottom: 60px;
                width: 45px;
                height: 30px;
                background-color: $light-shade;
            }
            
            &-body .window {
                @include center;
                top: 0px;
                width: 6px;
            }
            
            &-upper .window {
                @include center;
                top: 25px;
                width: 6px;
            }
            
            &-upper .gable-window {
                top: 10px;
            }
            
            &-upper {
                @include center;
                bottom: 75px;
                width: 30px;
                height: 55px;
                background-color: $house-white;	
                
                &:before {
                    content: "";
                    position: absolute;
                    width: 0;
                    height: 0;
                    top: -7px;
                    left: 0px;
                    border-top: 7px solid transparent;
                    border-right: 8px solid #F29F05;
                    border-bottom: 0px solid transparent;
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    top: -21px;
                    left: 4px;
                    width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 22px solid $house-white;
                }		
                
                &_shade {
                    position: absolute;
                    right: 70px;
                    bottom: 90px;
                    width: 30px;
                    height: 55px;
                    background-color: $light-shade;
                }
                
                &_shade:after {
                    content: "";
                    position: absolute;
                    top: -26px;
                    left: -4px;
                    width: 0;
            height: 0;
            border-left: 17px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 26px solid $light-shade;
                }		
            }
        }
    
        .tor-wrap {
            position: relative;
            left: 210px;
            bottom: 400px;
            width: 650px;
            flex: 1 0 auto;
            animation: infinity-loop 4s infinite linear .1s both;
        }
    
        .tor {
            position: relative;
            width: 150px;
            transform: scale(1.2);
            
            &:before {
                content: "";
                position: absolute;
                width: 0;
                height: 0;
                border-right: 5px solid black;
                border-top: 5px solid transparent;
                right: 2px;
                top: 8px;
                z-index: 3;
            }
            
            &-chariot_shade {
                @include center;
                width: 0;
                height: 0;
                border-left: 5px solid $light-shade;
                border-bottom: 6px solid transparent;
                top: -22px;
                right: -26px;
                z-index: 2;
                
                    &:before {
                        content: "";
                        position: absolute;
                        width: 0;
                        height: 0;
                        border-right: 4px solid $light-shade;
                        border-top: 5px solid transparent;
                        left: -26px;
                        top: -4px;
                }
            }
            
            &-chariot_outer {
                @include center;
                background-color: black;
                width: 8px;
                height: 10px;
                top: -25px;
                left: 33px;
            }
            
            &-chariot {
                @include center;
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                width: 21px;
                top: -22px;
                z-index: 6;
                
                &:before {
                    content: "";
                    background-color: $house-white;
                    width: 10px;
                    height: 4px;
                    flex: 1 0 100%;
                }
                
                &:after {
                    content: "";
                    background-color: $light-shade;
                    width: 22px;
                    height: 4px;						
                    position: absolute;
                    top: -4px;
                    left: 4px;			
                }
            }
            
            &-horse {
                background-color: $house-white;
                width:3px;
                height: 9px;
            }
            
            &-horse:nth-child(2) {
                    margin: 0 4px;
            }
            
            &-roof_shade {
                position: absolute;
                width: 5px;
                height: 7px;
                background-color: $light-shade;
                right: 50px;
                top: -12px;
                z-index: 5;
                
                &:before {
                    content: "";
                    position: absolute;
                    width: 10px;
                    height: 5px;
                    background-color: black;
                    top: -6px;
                    right: -3px;
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    width: 10px;
                    height: 6px;
                    background-color: black;
                    top: 3px;
                    left: -44px;
                }
            }
            
            &-roof {
                @include center;
                top: -18px;
                left: 3px;
                width: 33px;
                height: 15px;
                background-color: black;
                z-index: 5;
                
                &:before {
                    content: "";
                    position: absolute;
                    top: 5px;
                    right: -10px;
                    width: 0;
                    height: 0;
                    border-left: 10px solid black;
                    border-bottom: 10px solid transparent;
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: -9px;
                    width: 0;
                    height: 0;
                    border-right: 10px solid black;
                    border-top: 10px solid transparent;
                }
            }
            
            &-top {
                position: relative;
                margin-left: 0;
                width: 148px;
                height: 5px;
                background-color: $house-white;
                z-index: 3;
                margin-bottom: -6px;
                
                &:before {
                    content: "";
                    position: absolute;
                    top: 0px;
                    right: -6px;
                    width: 0;
                    height: 0;
                    border-left: 6px solid $light-shade;
                    border-bottom: 5px solid transparent;
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    top: -5px;
                    left: 0px;
                    width: 0;
                    height: 0;
                    border-right: 6px solid $light-shade;
                    border-top: 5px solid transparent;
                }
            }
            
            &-top_shade {
                position: absolute;
                top: -5px;
                left: 5px;
                width: 149px;
                height: 5px;
                background-color: $light-shade;
            }
            
            &-center {
                @include center;
                top: -10px;
                width: 40px;
                height: 18px;
                background-color: $house-white;
                border-right: 2px solid $light-shade;
                z-index: 4;
                
                &:before {
                    content: "";
                    position: absolute;
                    top: 16px;
                    right: -30px;
                    width: 0;
                    height: 0;
                    border-left: 30px solid black;
                    border-top: 5px solid transparent;
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    top: 16px;
                    left: -30px;
                    width: 0;
                    height: 0;
                    border-right: 30px solid black;
                    border-top: 5px solid transparent;
                }
            }
            
            &-upper {
                position: relative;
                margin-left: 5px;
                width: 138px;
                height: 8px;
                background-color: $house-white;
                
                &:before {
                    content: "";
                    position: absolute;
                    top: 3px;
                    right: -11px;
                    width: 9px;
                    height: 5px;
                    background-color: black;
                }
            }
            
            &-upper_shade {
                position: relative;
                margin-left: 5px;
                margin-bottom: -8px;
                width: 142px;
                height: 12px;
                background-color: $light-shade;
            }
            
            &-mid {
                position: relative;
                margin-bottom: -5px;
                margin-left: -3px;
                width: 150px;
                height: 7px;
                background-color: black;
                z-index: 2;
                
                &:before {
                    content: "";
                    position: absolute;
                    top: 0;
                    right: -7px;
                    width: 0;
                    height: 0;
                    border-left: 7px solid black;
                    border-bottom: 7px solid transparent;
                }
                
                &:after {
                    content: "";
                    position: absolute;
                    top: -7px;
                    left: -0px;
                    width: 0;
                    height: 0;
                    border-right: 9px solid black;
                    border-top: 7px solid transparent;
                }
            }
            
            &-shade {
                position: relative;
                margin-left: 3px;
                margin-bottom: -4px;
                width: 145px;
                height: 8px;
                background-color: $light-shade;
            }
            
            &-shade:after {
                content: "";
                position: absolute;
                top: 8px;
                right: 0px;
                width: 0;
                height: 0;
                border-top: 0;
                border-left: 5px solid $light-shade;
                border-bottom: 5px solid transparent;
            }
            
            &-shade:before {
                content: "";
                position: absolute;
                top: 8px;
                right: 0px;
                width: 5px;
                height: 5px;
                background-color: black;
            }
            
            &-lower {
                position: relative;
                margin-left: 3px;
                width: 140px;
                height: 8px;
                background-color: $house-white;
            }
            
            &-legs {
                display: flex;
            }
            
            &-leg {
                position: relative;
                background: linear-gradient(90deg, $house-white 0%, $house-white 60%, $light-shade 60%, $light-shade 100%);
                width: 10px;
                height: 90px;
                margin: 0 6px;
            }
            
            &-leg:before {
                content: "";
                position: absolute;
                top: 0;
                left: -3px;
                width: 12px;
                height: 5px;
                background-color: black;
            }
            
            &-leg:after {
                content: "";
                position: absolute;
                top: 0px;
                left: 9px;
                width: 0;
                height: 0;
                border-top: 0;
                border-left: 5px solid black;
                border-bottom: 5px solid transparent;
            }
            
            &-leg:nth-child(3) {
                margin-right: 25px;
            }
        }
    
        .dom-container {
            position: relative;
            right: 110px;
            bottom: 475px;
            width: 650px;
            flex: 1 0 auto;
            animation: infinity-loop 4s infinite linear .1s both;
        }
    
        .dom {
            position: relative;
            width: 200px;
            background-color: $light-shade;
            transform: scale(1.2);
            
            &-body {
                display: flex;
                align-items: flex-end;
                position: relative;
                height: 80px;
                background-color: $house-white;
                top: 15px;
                left: -12px;	
                
                &:before {
                    content: "";
                    position: absolute;
                    width: 0;
            height: 0;
                    top: -8px;
                    left: 0;
            border-top: 8px solid transparent;
            border-right: 7px solid $light-shade;
            border-bottom: 0px solid transparent;
                }
            }
            
            &-shade {
                @include center;
                background-color: $light-shade;
                top: -35px;
                left: 5px;
                width: 80px;
                height: 50px;
            }
            
            &-shade:before {
                content: "";
                position: absolute;
                background-color: $dome-blue;
                width: 6px;
                height: 8px;
                left: 23px;
                top: -37px;
                z-index: 1;
            }
            
            &-shade:after {
                content: "";
                position: absolute;
                background-color: $dome-dark;
                width: 20px;
                height: 10px;
                transform: rotate(-35deg);
                left: 59px;
                top: -4px;
            }	
            
            &-shade--l, &-shade--r {
                top: -30px;	
                width: 30px;
                height: 35px;
            }
            
            &-shade--l {
                margin-left: 0;
            }
            
            // Nipple
            
            &-shade--l:before, &-shade--r:before {
                content: "";
                position: absolute;
                background-color: $dome-blue;
                width: 5px;
                height: 5px;		
                left: 2px;
                top: -12px;
                z-index:1;
            }
            
            &-shade--l:after, &-shade--r:after {
                content: "";
                position: absolute;
                background-color: $dome-dark;
                width: 20px;
                height: 10px;
                transform: rotate(-35deg);
                left: 8px;
                top: -3px;
            }
            
            &-shade--r {
                margin-right: 0;
                right: 10px;
            }
            
            &-dome {
                @include center;
                top: -25px;
                left: -25px;
                width: 80px;
                height: 40px;
                background-color: $house-white;
                display: flex;
                justify-content: center;
                align-items: flex-end;
            }
            
            &-dome:before {
                content: "";
                @include center;
                top: -50px;
                left: 15px;
                background-color: $dome-dark;
                width: 80px;
                height: 40px;
                border-top-left-radius: 80px;
                border-top-right-radius: 80px;
            }
            
            &-dome:after {
                content: "";
                @include center;
                top: -40px;
                background-color: $dome-blue;
                width: 80px;
                height: 40px;
                border-top-left-radius: 80px;
                border-top-right-radius: 80px;
            }
            
            &-left, &-right {
                position: absolute;
                width: 30px;
                height: 40px;
                top: -25px;
                background-color: $house-white;
                display: flex;
                justify-content: center;
                align-items: flex-end;
                index: 1;
            }
            
            &-left:after, &-right:after {
                content: "";
                @include center;
                top: -13px;
                background-color: $dome-blue;
                width: 30px;
                height: 15px;
                border-top-left-radius: 25px;
                border-top-right-radius: 25px;
            }
                
            &-left:before, &-right:before {
                content: "";
                @include center;
                top: -20px;
                left: 10px;
                background-color: $dome-dark;
                width: 30px;
                height: 15px;
                border-top-left-radius: 80px;
                border-top-right-radius: 80px;
            }
            
            &-left {
                left: -5px;
            }
            
            &-right {
                right: 20px;
            }
            
            .windows {
                justify-content: space-around;
                width: 60px;
                height: 100%;
            }
            
            .window {
                height: 18px;
                margin: 3px;
            }
            
            .door {
                width: 50px;
                height: 50px;
                border-top-left-radius: 25px;
                border-top-right-radius: 25px;
            }
            
            .door:after {
                content: none;
            }
        }
    
        .trees {
            display: flex;
            align-items: flex-end;
            position: absolute;
            bottom: 100px;
            left: 0;
            width: 100%;
            
            &--1 {
                position: relative;
                animation: infinity-loop 2.5s infinite linear .1s both;
                flex: 1 0 auto;
                bottom: 550px;
            }
            
            &--2 {
                animation: infinity-loop 2.5s infinite linear .1s both;
                bottom: 270px;
                left: 50px;
                flex: 1 0 auto;
                position: relative;
            }
            
            &--2 .tree-4 {
                margin-left: auto;
                margin-right: 20px;
            }
            
            &--3 {
                position: relative;
                background-color: $tree-purple;
                background: linear-gradient(180deg, transparent 0%, transparent 50%, $tree-purple 50%,  $tree-purple 100%);
                top: 90px;	
                padding-bottom: 1.5rem;
                animation: infinity-loop 3s infinite linear .1s both;
                flex: 1 0 auto;
            }
            
            &--3 {
                left: 0;
            }
            
            &--3 .tree {
                background-color: $tree-purple;
                margin-left: -10px;
            }
            
            &--3 	.tree-1 {
                width: 85px;
                height: 85px;
                margin-left: -20px;
            }
            
            &--3 .tree-2 {
                width: 65px;
                height: 65px;
            }
            
            &--3 .tree-3 {
                margin-left: -5px;
            }
            
            &--3 .tree-6 {
                width: 65px;
                height: 65px;
            }
            
            &--3 .tree-7 {
                width: 75px;
                height: 75px;
            }
        }
    
        .tree {
            background-color: $tree-green;
            width: 55px;
            height: 55px;
            border-radius: 50%;	
            
            &-1 {
                width: 65px;
                height: 65px;
                margin-left: 30px;
            }
            
            &-2 {
                width: 55px;
                height: 55px;
                margin-left: -5px;
            }
            
            &-3 {
                width: 80px;
                height: 80px;
                margin-left: 20px;
            }
            
            &-4 {
                width: 60px;
                height: 60px;
                margin-left: -25px;
            }
            
            &-5 {
                width: 50px;
                height: 50px;
                margin-left: auto;
            }
        }
    
        .houses {
            display: flex;
            align-items: flex-end;
            position: relative;
            left: 0;
            right: 0;
            bottom: 560px;
            animation: infinity-loop 2.5s infinite linear .1s both;
            flex: 1 0 auto;
        }
    
        .house { 
            position: relative;
            background-color: $house-white;
            margin: 0 3px;
            
            // Depth Shadow
            
            &:before {
                position: absolute;
                bottom:0;
                right: -10px;
                content: "";
                width: 0;
                height: 0;
                border-bottom: 10px solid $bg-blue;
                border-left: 10px solid transparent;
            }
            
            // House 1
            
            &-1 {
                width: 80px;
                height: auto;
            }
            
            // Gable
            
            &-1:after {
                content: "";
                position: absolute;
                top: -20px;
                border-bottom: 20px solid $house-white;
                border-left: 20px solid transparent;
                border-right: 20px solid transparent;
                height: 0;
                width: 50%;
            }
            
            // House 2
            
            &-2 {
                width: 65px;
            }
            
            &-2 .window {
                height: 20px;
                margin: 8px 5px;
            }
            
            &-2 .door {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                width: 25px;
                height: 25px;
            }
            
            &-2 .door:after {
                width: 20px;
            }
            
            // House 3
            
            &-3 {
                width: 65px;
            }
            
            // Top Shadow
            
            &-3:after {
                position: absolute;
                top: -20px;
                left: 0;
                content: "";
                width: 0;
                height: 0;
                border-top: 10px solid transparent;
                border-bottom: 10px solid transparent;
                border-left: 10px solid $tree-green;
            }
            
            &-3 .door {
                width: 25px;
                height: 35px;
            }
            
            &-3 .door:after {
                width: 20px;
            }
            
            &-3 .gable {
                width: 45px;
            }
            
            // Curve gable
            
            &-3 .gable:after {
                width: 35px;
                height: 20px;
                top: -15px;
                border-top-left-radius: 35px;
                border-top-right-radius: 35px;
            }
            
            &-3 .gable-shade {
                position: absolute;
                top: -25px;
                left: 15px;
                height: 20px;
                width: 35px;
                border-top-left-radius: 35px;
                border-top-right-radius: 35px;
            }
            
            &-3 .gable-shade:before {
                content: "";
                position: absolute;
                top: 2px;
                left: -2px;
                content: "";
                width: 0;
                height: 0;
                border-top: 5px solid transparent;
                border-bottom: 5px solid $house-shade;
                border-left: 5px solid transparent;
            }
            
            // House 4
            
            &-4 {
                width: 65px;
            }
            
            &-4:after {
                position: absolute;
                top: -14px;
                left: -8px;
                content: "";
                width: 15px;
                height: 10px;
                border-top: 10px solid transparent;
                border-bottom: 0;
                border-left: 10px solid $tree-green;
                transform: rotate(90deg);
            }
            
            &-4 .windows {
                padding: 12px 8px 0;
            }
            
            &-4 .window {
                width: 10px;
                height: 10px;
                margin: 6px 7px;
            }
            
            &-4 .gable {
                width: 22px;
                height: 20px;
                top: -25px;
            }
            
            &-4 .gable:before {
                top: -35px;
                border-top: 18px solid transparent;
                border-bottom: 18px solid transparent;
                border-left: 18px solid $tree-green;
            }
            
            &-4 .gable:after {
                width: 35px;
                height: 35px;
                transform: rotate(-45deg);
                top: 8px;
                left: -7px;
            }
            
            &-4 .gable-window {
                top: 15px;
                width: 10px;
                height: 10px;
                border-radius: 0;
            }
            
            &-4 .gable-shade {
                position: absolute;
                top: -40px;
                left: 21px;
                height: 30px;
                width: 45px;
            
            }
            
            &-4 .gable-shade:before {
                top: 13px;
                left: 35px;
                border-top: 12px solid transparent;
                border-bottom: 12px solid transparent;
                border-left: 12px solid $house-shade;
                z-index: 1;
            }
            
            // House 5 
            
            &-5 {
                width: 80px;
            }
            
            &-5 .window {
                margin: 6px 7px;
            }
            
            // Gable
            
            &-5:after {
                content: "";
                position: absolute;
                top: -20px;		
                height: 20px;
                width: 100%;
                background-color: $house-white;
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }
            
            &-5 .gable {
                width: 42px;
                height: 40px;
                top: -35px;
                border-radius: 50%;
            }
            
            &-5 .gable:after, &-5 .gable:before {
                content: none;
            }
            
            &-5 .gable-window {
                top: 10px;
                width: 15px;
                height: 15px;
            }
            
            &-5 .gable-shade {
                top: -45px;
                left: 35px;
                width: 35px;
                height: 35px;
                border-radius: 50%;
            }
            
            &-5 .gable-shade:before {
                top: 3px;
                left: -9px;
                border-top: 15px solid transparent;
                border-bottom: 15px solid transparent;
                border-left: 15px solid $house-shade;
                transform: rotate(55deg);
            }
            
            &-5 .door {
                width: 35px;
                height: 30px;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
            }
            
            &-5 .door:after {
                width: 30px;
            }
            
            // House 6 -- end
            
            &-6 {
                width: 38px;
                margin: 0;
                margin-left: -4px;
            }
            
            &-6 .windows {
                padding: 12px 8px;
            }
            
            &-6 .window {
                width: 8px;
                height: 8px;
                margin: 6px;
            }
            
            &-6 .gable {
                width: 22px;
                margin-left: 0;
                left: 8px;
            }
            
            &-6 .gable:before {
                content: none;
            }
            
            &-6 .gable:after {
                top: -2px;
                left: -11px;
                margin-left: 0;
                width: 32px;
                transform: rotate(-45deg);
            }
            
            &-6 .gable-window {
                width: 8px;
                height: 8px;
                border-radius: 0;
                top: 3px;
            }
            
            &-6 .gable-shade {
                width: 25px;
                height: 15px;
                top: -15px;
            }
            
            &-6 .gable-shade:before {
                width: 23px;
                height: 25px;
                top: -20px;
                left: 7px;
                background-color: $house-shade;
                border: 0;
                transform: rotate(45deg);
            }
        }
    
        .shades {
            display: flex;
            align-items: flex-end;
            position: absolute;
            left: 15px;
            right: 0;
            bottom: 0;
        }
    
        .shade {
            position: relative;
            background-color: $house-shade;
            
            &-1 {
                width: 80px;
                height: 115px;
                left: 3px;
            }
            
            &-1:last-child {
                left: 12px;
            }
            
            &-1:last-child:before {
                content: "";
                position: absolute;
                background-color: $house-shade;
                width: 15px;
                height: 115px;
                right: -34px;
                top: -2px;
            }
    
            &-1:after {
                content: "";
                position: absolute;
                top: -20px;
                border-bottom: 20px solid $house-shade;
                border-left: 20px solid transparent;
                border-right: 20px solid transparent;
                height: 0;
                width: 40px;
            }
            
            &-2 {
                width: 65px;
                height: 110px;
            }
            
            &-2:after {
                content: "";
                position: absolute;
                top: -15px;
                left: 6px;
                background-color: $house-shade;
                height: 15px;
                width: 47px;
            }
            
            &-3 {
                width: 75px;
                height: 115px;
            }
            
            &-3:after {
                content: "";
                position: absolute;
                top: -15px;
                left: 10px;
                background-color: $house-shade;
                height: 20px;
                width: 55px;
            }
                    
            &-4 {
                width: 70px;
                height: 123px;
            }
            
            &-4:after {
                content: "";
                position: absolute;
                top: -25px;
                right: -5px;
                background-color: $tree-green;
                height: 25px;
                width: 22px;
                z-index:1;
            }
            
            &-5 {
                width: 85px;
                height: 120px;
                left: 5px;
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }
        }
    
        .windows {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            padding: 0 8px 8px;
        }
    
        .window {
            background-color: black;
            width: 8px;
            height: 15px;
            margin: 8px 5px;
        }
    
        .door {
            position: relative;
            background-color: black;
            width: 15px;
            height: 30px;
            margin: 0 auto;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            
            // Shadow
            
            &:after {
                content: "";
                position: absolute;
                bottom: 0;
                border-bottom: 5px solid $bg-blue;
                border-left: 5px solid transparent;
                border-right: 0;
                height: 0;
                width: 10px;
            }
        }
    
        .gable {
            @include center;
            top: -15px;
            background-color: $house-white;
            width: 40px;
            height: 15px;
            
            &-shade {
                position: absolute;
                top: -20px;
                left: 13px;
                background-color: $house-shade;
                height: 15px;
                width: 20px;
            }
            
            &-window {
                @include center;
                top: -5px;
                background-color: black;
                width: 13px;
                height: 13px;
                border-radius: 50%;
                z-index:1;
            }
            
            // Shade shape
            
            &-shade:before {
                content: "";
                position: absolute;
                top: -15px;
                left: -1px;
                width: 0;
                height: 0;
                border-top: 10px solid transparent;
                border-bottom: 10px solid transparent;
                border-left: 10px solid $tree-green;
            }
            
            // Top Step
            
            &:after {
                @include center;
                top: -15px;
                content: "";
                width: 15px;
                height: 15px;
                background-color: $house-white;
            }
            
            // Top shadow
            
            &:before {
                position: absolute;
                top: -20px;
                left: -1px;
                content: "";
                width: 0;
                height: 0;
                border-top: 10px solid transparent;
                border-bottom: 10px solid transparent;
                border-left: 10px solid $tree-green;
            }
    
        }
    
        @keyframes infinity-loop {
        from {
        transform: translateX(0);
        }
        to {
        transform: translateX(-100%);
        }
        }
    
        @keyframes move-clouds {
        from {
        transform: translateX(-3px);
        }
        to {
        transform: translateX(3px);
        }
        }
    </style>
 -->