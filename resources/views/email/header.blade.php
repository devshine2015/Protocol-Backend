<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Welcome to {{env('messages.APP_NAME')}}</title>
    <style>
        /* -------------------------------------
                        GLOBAL
        ------------------------------------- */
        * {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            font-size: 100%;
            line-height: 1.6;
        }

        img {
            max-width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
        }

        /* -------------------------------------
                        ELEMENTS
        ------------------------------------- */
        a {
            color: #348eda;
        }
        .admin-message{
             background-color:#FBFBFB;
             min-width: 600px;
             height: 100px;
             align-items: center;
             border: 1px solid #979797;
             border-radius: 3px;
             color:#6f6c6c;
             padding: 5px;
             overflow: auto;
             word-wrap: break-word;
             overflow-x:hidden;
             margin-bottom: 40px;
        }
        .admin-text{

        }
        .wrap{
            text-align:center
        }
        .share-left{
            /*margin-right: 20px;*/
            background-color:#FBFBFB;
            height: 100px;
            border: 1px solid #979797;
            float: left;
            color:#6f6c6c;
            overflow: auto;
            word-wrap: break-word;
            overflow-x:hidden;
            text-align: left;
            padding:5px;
            border-radius: 3px;
            min-width: 200px;
            max-width: 200px;
        }
        .share-right{
            padding: 5px;
            margin-top: 5px;
            background-color:#FBFBFB;
            height: 100px;
            border: 1px solid #979797;
            float: right;
            color:#6f6c6c;
            text-align: left;
            overflow: auto;
            word-wrap: break-word;
            overflow-x:hidden;
            min-width: 200px;
            max-width: 200px;
        }
        .note-content{
            background-color:#FBFBFB;
            height: 100px;
            border: 1px solid #979797;
            float: left;
            border-radius: 4px;
            color:#6f6c6c;
            text-align: center;
            overflow: auto;
            word-wrap: break-word;
            overflow-x:hidden;
            text-align: left;
            min-width: 250px;
            max-width: 250px;
            margin-right: 10px;
        }
        .share-center{
            background-color:#ee467a;
            min-height: 30px;
            align-items: center;
            border: 1px solid #ee467a;
            border-radius: 10px;
            display:inline-block;
            margin-top: 30px;
            border-radius: 8px;
            opacity: 0.7;
            color:#fff;
            text-align: center;
            min-width: 40px;
            max-width: 110px;

        }
        .share-tags {
            background: #ee467a;
            color: #fff;
            font-size: 14px;
            min-height: 35px;
            max-height: 35px;
            padding-top: 6px;
            border: 1px solid #979797;
            border-radius: 5px;
            margin: 12px;
            opacity: 0.7;
            text-align: center;
            display: table-cell;
            min-width: 100px;
        }
        .share-tags:nth-of-type(even) { background: #ee467a; }
        .mt{
            margin-top: 40px !important;
        }
        .note-title{
            padding: 5px;
            margin: 5px;
            background-color:#FBFBFB;
            min-height: 40px;
            border: 1px solid #979797;
            float: left;
            border-radius: 8px;
            color:#6f6c6c;
            text-align: left;
            min-width: 320px;
            max-width: 320px;
            margin-bottom: 15px;
        }
        .element-title{
            background-color:#FBFBFB;
            min-width: 600px;
            max-width: 600px;
            min-height: 40px;
            border: 1px solid #979797;
            float: left;
            border-radius: 8px;
            color:#6f6c6c;
            text-align: left
        }
        .note-support{
            background-color:#ee467a;
            align-items: right;
            border: 1px solid #ee467a;
            border-radius: 10px;
            display:inline-block;
            border-radius: 8px;
            opacity: 0.7;
            color:#fff;
            padding: 6px;
            min-width: 200px;
            max-width: 200px;
            margin : 18px;
        }
        .note-text{
            background-color:#FBFBFB;
            min-height:85px;
            align-items: right;
            border: 1px solid #979797;
            border-radius: 10px;
            display:inline-block;
            border-radius: 8px;
            opacity: 0.7;
            padding: 9px;
            text-align: left;
            overflow: auto;
            word-wrap: break-word;
            overflow-x:hidden;
            min-width: 300px;
            max-width: 300px;
        }
        .element-desc{
            background-color:#FBFBFB;
            min-height: 110px;
            min-width: 100px;
            align-items: right;
            border: 1px solid #979797;
            border-radius: 10px;
            display:inline-block;
            border-radius: 8px;
            opacity: 0.7;
            padding: 18px;
            text-align: center;
            min-width: 250px;
            max-width: 250px;
        }
        .mb{
            margin-bottom:40px !important;
        }
        .element-content{
            padding:5px;
            margin: 5px;
            background-color:#FBFBFB;
            height: 140px;
            border: 1px solid #979797;
            float: left;
            border-radius: 8px;
            color:#6f6c6c;
            text-align: left;
            overflow: auto;
            word-wrap: break-word;
            overflow-x:hidden;
            min-width: 250px;
            max-width: 250px;
        }

        .btn-primary, .btn-secondary {
            text-decoration: none;
            color: #FFF;
            background-color: #348eda;
            padding: 10px 20px;
            font-weight: bold;
            margin: 20px 10px 20px 0;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 25px;
        }

        .btn-secondary {
            background: #aaa;
        }

        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        /* -------------------------------------
                        BODY
        ------------------------------------- */
        table.body-wrap {
            width: 100%;
            padding: 20px;
        }

        table.body-wrap .container {
            border: 1px solid #f0f0f0;
        }

        /* -------------------------------------
                        FOOTER
        ------------------------------------- */
        table.footer-wrap {
            width: 100%;
            clear: both !important;
        }

        .footer-wrap .container p {
            font-size: 12px;
            color: #666;

        }

        table.footer-wrap a {
            color: #999;
        }

        /* -------------------------------------
                        TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3 {
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            line-height: 1.1;
            margin-bottom: 15px;
            color: #000;
            margin: 40px 0 10px;
            line-height: 1.2;
            font-weight: 200;
        }

        h1 {
            font-size: 36px;
        }

        h2 {
            font-size: 28px;
        }

        h3 {
            font-size: 22px;
            letter-spacing: 5px;
        }

        p, ul {
            margin-bottom: 10px;
            font-weight: normal;
            font-size: 14px;
        }

        ul li {
            margin-left: 5px;
            list-style-position: inside;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 0;
            border-top: 1px solid #eee;
        }

        /* ---------------------------------------------------
                        RESPONSIVENESS
                        Nuke it from orbit. It's the only way to be sure.
        ------------------------------------------------------ */

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important; /* makes it centered */
            clear: both !important;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            display: block;
        }

        /* Let's make sure tables in the content area are 100% wide */
        .content table {
            width: 100%;
        }

        small {
            color: #999;
            font-size: 12px;
        }

    </style>
</head>