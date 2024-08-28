@extends('layouts.app-master')

@section('faqs', 'active')
@section('faqs', "FAQs")
@section('title', "FAQs | TripleSMS")

@section('style')
<style>
	.panel-group .panel {
		border-radius: 0;
		box-shadow: none;
		border-color: #EEEEEE;
	}

	.panel-default > .panel-heading {
		padding: 0;
		border-radius: 0;
		color: #212121;
		background-color: #FAFAFA;
		border-color: #EEEEEE;
	}

	.panel-title {
		font-size: 14px;
	}

	.panel-title > a {
		display: block;
		padding: 15px;
		text-decoration: none;
        color: #212529;
        font-size: 18px;
        font-weight: 500;
	}

	.more-less {
		float: right;
		color: #212121;
	}

	.panel-default > .panel-heading + .panel-collapse > .panel-body {
		border-top-color: #EEEEEE;
	}

    .panel-body{
        padding:30px;
        font-size:15px;
    }

    .demo {
        padding-top: 60px;
        padding-bottom: 110px;
    }
    @media only screen and (max-width:575px){
        .panel-title > a {
            font-size: 15px;
	    }
        .panel-body{
            padding:15px;
            font-size:14px;
        }
    }
</style>
@endsection

@section('content')

<div class="container user demo" style="max-width:800px;">
    <h4 class="m-b-40">Most Popular Questions</h4>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        @foreach($articles as $article)
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="{{'#acc'.$article->id}}" aria-expanded="true" aria-controls="collapseOne">
                            <i class="more-less fa fa-plus"></i>
                            {{$article->title}}
                        </a>
                    </h4>
                </div>
                <div id="{{'acc'.$article->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        {{$article->answers}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('script')
<script>
	function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('fa-plus fa-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);
</script>
@endsection