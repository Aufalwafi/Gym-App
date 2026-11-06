import '../css/app.css';
import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import * as Lucide from 'lucide';

window.Alpine = Alpine;
window.Chart = Chart;
window.Lucide = Lucide;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    if (window.Lucide && window.Lucide.createIcons) {
        window.Lucide.createIcons();
    }
});
