<?php

return [
    'admin' => ['*'],
    'doctor' => ['doctors.*', 'reports.*'],
    'staff' => ['users.view', 'pharmacies.view'],
];
