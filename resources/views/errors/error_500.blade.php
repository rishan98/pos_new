<style>
    body {
        background: #141829;
        font-family: 'Nova Square', cursive;
    }

    .text-404 {
        margin-top: 80px;
        font-size: 200px;
        line-height: 200px;
        text-align: center;
        letter-spacing: 5px;
        background: -webkit-linear-gradient(#FF2525, #FFE53B);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0px;
        padding-bottom: 0px;
    }

    .text {
        text-align: center;
        font-size: 20px;
        color: #f6f6e3;
        letter-spacing: 5px;
        margin-top: 0px;
    }

    .text-btn {
        text-align: center;
        margin-top: 75px;
    }

    .btn-outline-primary {
        border-color: #ffc8c8;
        color: #ffc8c8;
        border-radius: 0px;
        font-weight: bold;
        letter-spacing: 1px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    }

    .btn-outline-primary:hover {
        background-color: #ffc8c8;
        right: 0px;
        border-color: #ffc8c8;
        color: #141829;
    }

    @media screen and (max-width:500px) {
        .text-404 {
            font-size: 150px;
        }
    }

    @media screen and (max-width:345px) {
        .text-404 {
            font-size: 120px;
        }
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="col-lg-12 text-404">
                <b>500</b>
            </div>
            <div class="col-lg-12 text">
                <b> Internal Server Error </b>
            </div>
            <br>
            <div class="col-lg-12">
                <p class="text">{{ $error}}</p>
            </div>
            <div class="col-lg-12 text-btn">
                <a href="index.php"><button name="login" class="btn btn-outline-primary">BACK TO HOME</button></a>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
</div>