{% extends "base.php" %}
{% block title %} Access Denied{% endblock %}
{% block type %} Access Denied{% endblock %}
{% block additionalCSS %}
<style>
    @color-primary: #30A9DE;
    @color-secondary: #30A9DE;
    @color-tertiary: #30A9DE;
    @color-primary-light: #6AAFE6;
    @color-primary-dark: #8EC0E4;
    @Distance: 1000px;

    body{
        overflow: hidden;
    }

    html, body {
        position: relative;
        min-height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #274c5e;
    }

    .Container {
        text-align: center;
        position: relative;
    }

    .MainTitle {
        display: block;
        font-size: 2rem;
        font-weight: lighter;
        text-align: center;
    }

    .MainDescription {
        max-width: 50%;
        font-size: 1.2rem;
        font-weight: lighter;
    }

    .MainGraphic {
        position: relative;
    }

    .Cog {
        width: 10rem;
        height: 10rem;
        fill: #F36E21;
        transition: easeInOutQuint();
        animation: CogAnimation 5s infinite;
    }

    .Hummingbird{
        position: absolute;
        width: 3rem;
        height: 3rem;
        fill: @color-primary;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
    }

    @keyframes CogAnimation {
        0%   {transform: rotate(0deg);}
        100% {transform: rotate(360deg);}
    }

    @keyframes SpannerAnimation {
        0%   {
            transform:
                translate3d(20px, 20px,1px)
                rotate(0deg);
        }
        10% {
            transform:
                translate3d(-@Distance, @Distance, 1px)
                rotate(180deg);
        }
        15% {
            transform:
                translate3d(-@Distance, @Distance, 1px)
                rotate(360deg);
        }
        20% {
            transform:
                translate3d(@Distance, -@Distance, 1px)
                rotate(180deg);
        }
        30% {
            transform:
                translate3d(-@Distance, @Distance, 1px)
                rotate(360deg);
        }
        40% {
            transform:
                translate3d(@Distance, -@Distance, 1px)
                rotate(360deg);
        }
        50% {
            transform:
                translate3d(-@Distance, @Distance, 1px)
                rotate(180deg);
        }
        100% {
            transform:
                translate3d(0, 0px, 0px)
                rotate(360deg);
        }
    }



    .tiger {
        height: 200px;
        left: 0;
        margin: auto;
        position: relative;
        right: 0;
        width: 100px;
    }

    .head {
        background-color: #BF7F52;
        border-radius: 50% 50% 100% 100%;
        height: 60px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        width: 75px;
    }

    .ear {
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-bottom: 12px solid #BF7F52;
        border-radius: 100%;
        position: absolute;
        top: -5px;
        width: 0;
    }
    .ear:after {
        border-left: 9px solid transparent;
        border-right: 9px solid transparent;
        border-bottom: 6px solid #222;
        border-radius: 100%;
        content: '';
        position: absolute;
    }
    .ear.left {
        left: -3px;
        transform: rotate(200deg);
        -webkit-transform: rotate(200deg);
        -moz-transform: rotate(200deg);
        -ms-transform: rotate(200deg);
    }
    .ear.left:after {
        left: -8px;
        top: 3px;
    }
    .ear.right {
        right: -3px;
        transform: rotate(-200deg);
        -webkit-transform: rotate(-200deg);
        -moz-transform: rotate(-200deg);
        -ms-transform: rotate(-200deg);
    }
    .ear.right:after {
        right: -8px;
        top: 3px;
    }

    .eye {
        background-color: #222;
        border-radius: 100%;
        height: 8px;
        position: absolute;
        top: 18px;
        width: 8px;
    }
    .eye.left {
        left: 19px;
    }
    .eye.right {
        right: 19px;
    }

    .nose {
        background-color: #DECEC3;
        border-radius: 100%;
        height: 30px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 12px;
        width: 16px;
    }
    .nose:after {
        background-color: #222;
        border-radius: 30% 30% 100% 100%;
        content: '';
        height: 8px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 18px;
        width: 14px;
    }
    .nose:before {
        background-color: #DECEC3;
        border-radius: 100%;
        content: '';
        height: 30px;
        left: -17px;
        margin: auto;
        position: absolute;
        right: 0;
        top: 18px;
        width: 50px;
    }

    .tongue {
        background-color: #CC4141;
        border-radius: 100%;
        height: 10px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 44px;
        width: 10px;
    }

    .mouth, .mouth:after {
        border-bottom: 2px solid #222;
        border-radius: 100%;
        height: 10px;
        left: -14px;
        margin: auto;
        position: absolute;
        right: 0;
        top: 36px;
        width: 14px;
    }
    .mouth:after {
        content: '';
        left: 13px;
        top: 0;
        z-index: 2;
    }

    .details, .details:after, .details-body:after {
        border-bottom: 5px solid transparent;
        border-top: 5px solid transparent;
        position: absolute;
        top: 16px;
    }
    .details:after, .details-body:after {
        content: '';
        top: 8px;
    }
    .details.left, .left.details:after, .left.details-body:after {
        border-left: 10px solid #222;
        left: 0;
    }
    .details.left:after, .left.details-body:after {
        border-left: 8px solid #222;
        left: -10px;
    }
    .details.right, .right.details:after, .right.details-body:after {
        border-right: 10px solid #222;
        right: 0;
    }
    .details.right:after, .right.details-body:after {
        border-right: 8px solid #222;
        right: -10px;
    }

    .body {
        height: 100px;
        margin: auto;
        position: absolute;
        right: 0;
        width: 100px;
    }

    .back {
        background-color: #b07043;
        border-radius: 100% 100% 40% 40%;
        height: 35px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 103px;
        width: 90px;
    }

    .details-body {
        border-bottom: 4px solid transparent;
        border-top: 4px solid transparent;
        border-radius: 40%;
        position: absolute;
        top: 12px;
    }
    .details-body:after {
        border-radius: 40%;
        content: '';
        top: 6px;
    }
    .details-body.left {
        border-left: 8px solid #222;
        left: 0;
    }
    .details-body.left:after {
        left: -8px;
    }
    .details-body.right {
        border-right: 8px solid #222;
        right: 0;
    }
    .details-body.right:after {
        right: -8px;
    }

    .main {
        background-color: #BF7F52;
        border-radius: 20% 20% 0 0;
        height: 85px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 50px;
        width: 65px;
    }
    .main:after {
        background-color: #DECEC3;
        border-radius: 100%;
        content: '';
        height: 40px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 14px;
        width: 35px;
    }
    .main:before {
        background-color: #b07043;
        border-radius: 30% 30% 0 0;
        content: '';
        height: 27px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 60px;
        width: 24px;
    }

    .hand {
        background-color: #DECEC3;
        border-radius: 100% 100% 0 0;
        height: 7px;
        position: absolute;
        top: 130px;
        width: 20px;
    }
    .hand.left {
        left: 18px;
    }
    .hand.right {
        right: 18px;
    }

    .tail {
        background-color: #b07043;
        border-radius: 40%;
        height: 100px;
        left: 5px;
        position: absolute;
        top: 40px;
        width: 12px;
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
    }
    .tail:after {
        background-color: #222;
        border-radius: 100% 100% 0 0;
        content: '';
        height: 10px;
        position: absolute;
        width: 11px;
    }
    .tail .details, .tail .details:after, .tail .details-body:after {
        border-left: 6px solid #222;
        border-bottom: 3px solid transparent;
        border-top: 3px solid transparent;
        position: absolute;
        width: 0;
    }
    .tail .details:after, .tail .details-body:after {
        display: none;
    }
    .tail .details.d1, .tail .d1.details:after, .tail .d1.details-body:after {
        top: 15px;
    }
    .tail .details.d2, .tail .d2.details:after, .tail .d2.details-body:after {
        top: 25px;
    }
    .tail .details.d3, .tail .d3.details:after, .tail .d3.details-body:after {
        top: 35px;
    }
    .tail .details.d4, .tail .d4.details:after, .tail .d4.details-body:after {
        top: 45px;
    }
    .tail .details.d5, .tail .d5.details:after, .tail .d5.details-body:after {
        top: 55px;
    }

</style>
{% endblock %}

{% block body %}

<div class="Container">

    <div class="MainGraphic">
        <div class="tiger">
            <div class="body">
                <div class="tail">
                    <div class="details d1"></div>
                    <div class="details d2"></div>
                    <div class="details d3"></div>
                    <div class="details d4"></div>
                    <div class="details d5"></div>
                </div>
                <div class="back">
                    <div class="details-body left"></div>
                    <div class="details-body right"></div>
                </div>
                <div class="main"></div>
                <div class="hand left"></div>
                <div class="hand right"></div>
            </div>
            <div class="head">
                <div class="details left"></div>
                <div class="details right"></div>
                <div class="ear left"></div>
                <div class="ear right"></div>
                <div class="eye left"></div>
                <div class="eye right"></div>
                <div class="nose"></div>
                <div class="tongue"></div>
                <div class="mouth"></div>
            </div>
        </div>
    </div>
    <h1 class="MainTitle">
        Invalid Session. You are likely to have been send a link from someone or trying to visit a old link
    </h1>
    <div class="Main Description">
        <h3><a href="{{url('')}}">Go to Homepage</a></h3>
        <hr/>
    </div>

</div>
{% endblock %}