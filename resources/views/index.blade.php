@extends('layout.pageLayout')

@section('body')
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">MageAudit</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <!-- Begin page content -->
    <div class="container">
        <div class="page-header">
            <h1>Welcome to MageAudit</h1>
        </div>
        <div class="search-bar">
            <div class="row">
                <div class="col-sm-8">
                    <input id="input-domain" class="domain-input" type="text" name="domain" placeholder="Input Base Domain (required)" />
                </div>
                <div class="col-sm-2">
                    <button id="search" class="btn-primary">Scan</button>
                </div>
                <div class="col-sm-2">
                    <button id="clear" class="btn-primary">Clear</button>
                </div>
            </div>
        </div>
    </div>

    <div id="myProgress">
        <div id="myBar"></div>
    </div>

    <div id="myProgressValue">

    </div>

    <div class="container subscribe">
        <h2>Input email to download Report now</h2>
        <div class="row">
            <div class="col-sm-8">
                <input id="input-email" class="domain-input" type="email" name="email" placeholder="Please input your Email Address" />
            </div>
            <div class="col-sm-4">
                <button id="subscribe" class="btn-info">Download Now</button>
            </div>
        </div>
    </div>

    <div class="container result">
        <div class="container-security">

        </div>

        <div class="container-seo">

        </div>
    </div>

    <div id="error-image">
        <a href="/"><img src="{{ URL::asset('images/error.png') }}" /></a>
    </div>

    <form id="pdfForm" style="display:none;" action="/security/createreport" method="post">
        <input id="html" name="html" />
        <input id="domain" name="domain" />
    </form>

    <footer class="footer">
        <div class="container">
            <p class="text-muted">© 2017 OODDA Inc. All rights reserved.</p>
        </div>
    </footer>
@endsection