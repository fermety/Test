@extends('layouts.app')

@push('title')
    <title>Парковка | {{ config('app.name') }}</title>
@endpush

@section('app')
    <main x-data="parking" x-cloak class="flex items-center w-full">
        <div x-show="screen === 'scan_сard'" class="flex flex-col items-center w-full gap-5 mt-20">
            <input
                type="number"
                required
                x-model="numberСard"
                class="flex h-[55px] w-1/3 rounded font-medium px-[25px] py-5"
                placeholder="Введите номер карты">

            <button @click="scanCard" class="flex h-[47px] w-1/3 bg-blue-500 items-center justify-center border-0 rounded-md text-sm font-bold">
                Отправить
            </button>

            <span class="text-red-500" x-text="textError"></span>
        </div>

        <div x-show="screen === 'not_seats'" class="flex flex-col items-center w-full gap-5 mt-20">
            <span class="text-red-500">извините, свободных мест нет</span>
        </div>

        <div x-show="screen === 'let_car'" class="flex flex-col items-center w-full gap-5 mt-20">
            <span>Тариф: {{ config('settings.tariff') }} &#8381;/минута</span>

            <span x-text="'Свободных мест: ' + freeSeats"></span>

            <button x-show="freeSeats" @click="letCar" class="flex h-[47px] w-1/3 bg-blue-500 items-center justify-center border-0 rounded-md text-sm font-bold">
                Припарковаться
            </button>

            <span class="text-red-500" x-text="textError"></span>
        </div>

        <div x-show="screen === 'successful_parking'" class="flex flex-col items-center w-full gap-5 mt-20">
            <span class="text-green-500">
                проезжайте, ваше место <span x-text="name_space"></span>
            </span>
        </div>

        <div x-show="screen === 'pick_up_car'" class="flex flex-col items-center w-full gap-5 mt-20">
            <span x-text="'Ваше время: ' + infoPickUpCar.session_duration + ' минут'"></span>

            <span>
                Стоимость: <span x-text="infoPickUpCar.session_duration * {{ config('settings.tariff') }}"></span> &#8381;
            </span>

            <span x-text="'Время начала: ' + infoPickUpCar.createdAt"></span>

            <button @click="releaseCar" class="flex h-[47px] w-1/3 bg-blue-500 items-center justify-center border-0 rounded-md text-sm font-bold">
                Забрать машину
            </button>

            <span class="text-red-500" x-text="textError"></span>
        </div>

        <div x-show="screen === 'successful_check_out'" class="flex flex-col items-center w-full gap-5 mt-20">
            <span class="text-green-500">
                Хорошей дороги :&#41;
            </span>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('parking', () => ({
                numberСard: '',
                textError: '',
                screen: 'scan_сard',
                freeSeats: '',
                infoPickUpCar: {},
                name_space: '',

                scanCard() {
                    axios.get(`/api/v1/users/scan-card/${this.numberСard}`)
                        .then((response) => {
                            if (response.data.is_parked) {
                                this.infoPickUpCar.createdAt = response.data.created_at;
                                this.infoPickUpCar.session_duration = response.data.session_duration;

                                this.screen = 'pick_up_car';
                            } else {
                                this.freeSeats = response.data.free_seats;

                                this.screen = this.freeSeats == 0 ? 'not_seats' : 'let_car';
                            }
                        }).catch(() => {
                            this.textError = "Пользователь не найден";
                        });
                },

                letCar() {
                    axios.get(`/api/v1/users/let-car/${this.numberСard}`)
                        .then((response) => {
                            if (response.data.success) {
                                this.name_space = response.data.name_space;

                                this.screen = 'successful_parking';
                            } else {
                                this.textError = response.data.error;
                            }
                        }).catch(() => {
                            this.textError = "Не удалось припарковать машину";
                        });
                },

                releaseCar() {
                    axios.get(`/api/v1/users/release-car/${this.numberСard}`)
                        .then((response) => {
                            if (response.data.success) {
                                this.screen = 'successful_check_out';
                            } else {
                                this.textError = response.data.error;
                            }
                        }).catch(() => {
                            this.textError = "Не удалось выпустить машину";
                        });
                },
            }))
        })
    </script>
@endpush
