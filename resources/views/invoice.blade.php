<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <style>
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .logostyle{
            width: 300px;
            float: left;
            clear:left;
        }
        .infostyle{
            width:250px;
            text-align: right;
            float: right;
            clear: right;
            margin-top: 5px;
        }
        .linestyle{
            height: 1px;
            border: none;
            color:#ddd;background-color:#ddd;
            margin-top: 30px;
        }
        .table{
            width: 100%;
            order-spacing: 0;
            border-collapse: collapse
        }
        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
            height:50px;

        }
        .table>thead>tr{
            height:100px;
        }
        .table>tbody>tr{
            height:100px;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .m-t-30{
            margin-top: 30px;
        }

        .row {
            margin-right: -10px;
            margin-left: -10px;
        }

        h4{
            font-size:18px;
            margin-top: 10px;
            margin-bottom: 10px;
            font-family: inherit;
            font-weight: 500;
            line-height: 1.1;
            color: inherit;
            display: block;
            -webkit-margin-before: 1.33em;
            -webkit-margin-after: 1.33em;
            -webkit-margin-start: 0px;
            -webkit-margin-end: 0px;
        }

        b{
            font-weight: 700;
        }

        .label {
            display: inline;
            padding: 5px;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #ddd;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 10px;
            height: 32px;
            background-color: #ebc142;
        }
        .label-warning {
            background-color: #ebc142;
        }
        .label-danger {
            background-color: #FF6C60;
        }
        .label-success {
            background-color: #2eb398;
        }
        .parentstyle{
            width: 300px;
            float: left;
            clear:left;
        }

        .invoiceinfo{
            width:250px;
            text-align: right;
            float: right;
            clear: right;
            padding: 5px;
            margin-bottom: 10px;

            display: block;
        }
        .bb .invoiceinfo  p{
            margin: 0px;
            display: block;
            -webkit-margin-before: 1em;
            -webkit-margin-after: 1em;
            -webkit-margin-start: 0px;
            -webkit-margin-end: 0px;
        }


    </style>
</head>
<body >
    <div style="display: block" class="bb">
        <div class="clearfix">
            <div class="logostyle clearfix">
                <div style="float: left;">
                    <img src="{{public_path().'/img/logo_and_text.png'}}" style="width: 256px;height: auto;float:left;">
                </div>
                {{--<div style="float: right;margin-top: 10px;">--}}
                    {{--<h2 style="margin-top: 0px;margin-left:0px;padding: 0px;">--}}
                       {{--SMS POH MAL'--}}
                    {{--</h2>--}}
                {{--</div>--}}
            </div>
            <div class="infostyle">
                NaGyi 8/31, Kyar Nyo Street, On Ngu Shwe War Road, Between 62 & 63 Street, Mandalay, Myanmar<br>
                <abbr title="Phone">P:</abbr>+95 950 741 49<br>
                info@lfuturedev.com<br>

            </div>

        </div>
        <hr class="linestyle">
        <div class="clearfix">
            <div class="parentstyle">
                Invoice To <br>
                {{Auth::user()->full_name}}<br>
                {{Auth::user()->mobile}}<br>
                {{Auth::user()->email}}
            </div>
            <div class="invoiceinfo">
                Invoice Number:  123456<br>
                Invoice Date: {{$invoice->order_date}}<br>
                Status: <strong>PAID</strong>
            </div>
        </div>
        <table class="table m-t-30">
            <thead>
            <tr>

                <th style="width: 30%;">Package</th>
                <th style="width:20%;text-align: center;">SMSs</th>
                <th style="text-align: right;width: 30%;">Amount</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <span>{{$invoice->package->packageName}}</span>
                </td>
                <td style="text-align: center;">
                    <span>{{number_format($invoice->total_sms)}}</span>
                </td>
                <td style="text-align: right"><span>MMK </span>{{number_format($invoice->cost)}}</td>
            </tr>
            <tr>

                <td></td>
                <td style="text-align: right;"><h4>Total </h4></td>
                <td style="text-align: right;"><h4><span>MMK </span>{{number_format($invoice->cost)}}</h4></td>
            </tr>
            </tbody>
        </table>
    </div>
</body>
</html>