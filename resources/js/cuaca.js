document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.weather-hour-box');
    const temp = document.getElementById('temp');
    const humidity = document.getElementById('humidity');
    const rain = document.getElementById('rain');
    const precip = document.getElementById('precip');
    const selectedTime = document.getElementById('selected-time');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            temp.textContent = btn.dataset.temp + '°C';
            humidity.textContent = 'Kelembaban: ' + btn.dataset.humidity + '%';
            rain.textContent = 'Curah Hujan: ' + btn.dataset.rain + ' mm';
            precip.textContent = 'Peluang Hujan: ' + btn.dataset.precip + '%';
            selectedTime.textContent = 'Jam: ' + btn.dataset.time;
        });
    });
});
