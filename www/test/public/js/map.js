(function(){
    form = document.querySelector('.orderForm');
    if(form) {
        form.addEventListener('keydown', function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
            }
        });
    }

    ymaps.ready(init);

    function init() {
        // Подключаем поисковые подсказки к полю ввода.
        var fromData = {
            "suggest":          "#address_from",
            "notice":           "#noticeFrom",
            "coordinares":      "#coordinares_from",
            "map":              "#mapFrom",
            "mapCode":          "mapFrom",
            "placemark":        "placemarkFrom",
            "mapObj":           "mapFrom"
        };

        var toData = {
            "suggest":          "#address_to",
            "notice":           "#noticeTo",
            "coordinares":      "#coordinares_to",
            "map":              "#mapTo",
            "mapCode":          "mapTo",
            "placemark":        "placemarkTo",
            "mapObj":           "mapTo"

        };
        var suggestViewFrom = new ymaps.SuggestView('address_from');
        var suggestViewTo = new ymaps.SuggestView('address_to');

        // При клике по кнопке запускаем верификацию введёных данных.

        $('.buttonFrom').bind('click', function (e) {
            geocode(fromData);
        });
        $('.buttonTo').bind('click', function (e) {
            geocode(toData);
        });




        function geocode(data) {
            // Забираем запрос из поля ввода.
            var request = $(data.suggest).val();
            // Геокодируем введённые данные.
            ymaps.geocode(request).then(function (res) {
                var obj = res.geoObjects.get(0),
                    error, hint;

                if (obj) {
                    // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
                    switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                        case 'exact':
                            break;
                        case 'number':
                        case 'near':
                        case 'range':
                            error = 'Неточный адрес, требуется уточнение';
                            hint = 'Уточните номер дома';
                            break;
                        case 'street':
                            error = 'Неполный адрес, требуется уточнение';
                            hint = 'Уточните номер дома';
                            break;
                        case 'other':
                        default:
                            error = 'Неточный адрес, требуется уточнение';
                            hint = 'Уточните адрес';
                    }
                } else {
                    error = 'Адрес не найден';
                    hint = 'Уточните адрес';
                }

                // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
                if (error) {
                    clearCoordinates(data);
                    showError(data,error);
                    //showMessage(data,hint);
                } else {
                    setCoordinates(data,obj);
                    showResult(data,obj);
                }
            }, function (e) {
                console.log(e)
            })

        }
        function showResult(data,obj) {
            // Удаляем сообщение об ошибке, если найденный адрес совпадает с поисковым запросом.
            $(data.suggest).removeClass('input_error');
            $(data.notice).css('display', 'none');

            var mapContainer = $(data.map),
                bounds = obj.properties.get('boundedBy'),
                // Рассчитываем видимую область для текущего положения пользователя.
                mapState = ymaps.util.bounds.getCenterAndZoom(
                    bounds,
                    [mapContainer.width(), mapContainer.height()]
                ),
                // Сохраняем полный адрес для сообщения под картой.

                address = [obj.getCountry(), obj.getAddressLine()].join(', '),
                // Сохраняем укороченный адрес для подписи метки.
                shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
            // Убираем контролы с карты.
            mapState.controls = [];
            // Создаём карту.
            createMap(data, mapState, shortAddress);
            // Выводим сообщение под картой.
            //showMessage(fromData,address);


        }

        function showError(data,message) {
            console.dir(message);
            console.dir(data.notice);
            $(data.notice).text(message);
            $(data.suggest).addClass('input_error');
            $(data.notice).css('display', 'block');
            // Удаляем карту.
            if (data.mapObj) {
                data.mapObj.destroy();
                data.mapObj = null;
            }
        }

        function createMap(data, state, caption) {
            // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
            if (!(data.mapObj instanceof Object)) {
                data.mapObj = new ymaps.Map(data.mapCode, state);
                placemark1 = new ymaps.Placemark(
                    data.mapObj.getCenter(), {
                        iconCaption: caption,
                        balloonContent: caption
                    }, {
                        preset: 'islands#redDotIconWithCaption'
                    });
                data.mapObj.geoObjects.add(placemark1);
                // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
            } else {
                data.mapObj.setCenter(state.center, state.zoom);
                placemark1.geometry.setCoordinates(state.center);
                placemark1.properties.set({iconCaption: caption, balloonContent: caption});
            }
        }

        function showMessage(data,message) {
        //    $(fromData.messageHeader).text('Данные получены:');
        //    $(fromData.message).text(message);
        }
        function setCoordinates(data,obj){
            var coord=[obj.geometry._coordinates[0], obj.geometry._coordinates[1]];
            $(data.coordinares).val(JSON.stringify(coord));
        }
        function clearCoordinates(data){
            $(data.coordinares).val('');
        }
    }

})();