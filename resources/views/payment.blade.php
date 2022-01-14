@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if($errors->any())
                    <div class='alert-danger alert'>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h5><i class="icon fas fa-check"></i> Success!</h5>
                            <p>{{ $message }}</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                <div class="card">
                    <div class="card-header">{{ __('Payment') }}</div>

                    <div class="card-body">
                        <form class="needs-validation" method="POST" action="{{ route('stripe.post') }}" novalidate>
                            @csrf

                            <div class="row mb-3">
                                <label for="card_name"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Name on Card') }}</label>

                                <div class="col-md-6">
                                    <input id="card_name" type="text" class="form-control" name="card_name"
                                           value="{{ old('card_name') }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="card_number"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Card Number') }}</label>

                                <div class="col-md-6">
                                    <input id="card_number" type="text" class="form-control" name="card_number"
                                           value="{{ old('card_number') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="card_exp_month"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Card Expiration Date') }}</label>

                                <div class="col-md-3">
                                    <select id="card_exp_month" class="form-select" name="card_exp_month">
                                        <option selected>Choose...</option>
                                        @foreach($months as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <select id="card_exp_year" class="form-select" name="card_exp_year">
                                        <option selected>Choose...</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="card_cvc"
                                       class="col-md-4 col-form-label text-md-end">{{ __('CVC') }}</label>

                                <div class="col-md-6">
                                    <input id="card_cvc" type="password" class="form-control" name="card_cvc" required>
                                </div>
                            </div>

                            <div class='form-row row'>
                                <div class='col-md-12 error form-group d-none'>
                                    <div class='alert-danger alert'>Please correct the errors and try again.</div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-4 offset-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Pay RM 53.00') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript">
        $(function () {
            const form = ('.needs-validation');

            $(form).submit(function (e) {
                e.preventDefault();

                try {
                    Stripe.setPublishableKey('{!! config('services.stripe.public') !!}');
                    Stripe.createToken({
                        number: $('#card_number').val(),
                        cvc: $('#card_cvc').val(),
                        exp_month: $('#card_exp_month').val(),
                        exp_year: $('#card_exp_year').val()
                    }, stripeResponseHandler);
                } catch (e) {
                    console.log(e);
                    alert('An unknown error has been occurred. Please contact admin.');
                }
            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error').removeClass('d-none').find('.alert').text(response.error.message);
                } else {
                    var token = response['id'];
                    $(form).append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $(form).get(0).submit();
                }
            }
        });
    </script>
@endpush
