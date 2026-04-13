import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// jQuery & Select2 are loaded via CDN in the layout for proper load-order compatibility.

