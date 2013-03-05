INSERT IGNORE INTO t_data
(date, frame, 
analog1, analog2, analog3, analog4, analog5, analog6, analog7, analog8, analog9, analog10, analog11, analog12, analog13, analog14, analog15, analog16,
digital1, digital2, digital3, digital4, digital5, digital6, digital7, digital8, digital9, digital10, digital11, digital12, digital13, digital14, digital15, digital16,
power1, power2, energy1, energy2)
SELECT
    date,
    'frame1',
    a1.value,
    a2.value,
    a3.value,
    a4.value,
    a5.value,
    a6.value,
    a7.value,
    a8.value,
    a9.value,
    a10.value,
    a11.value,
    a12.value,
    a13.value,
    a14.value,
    a15.value,
    a16.value,
    d1.value,
    d2.value,
    d3.value,
    d4.value,
    d5.value,
    d6.value,
    d7.value,
    d8.value,
    d9.value,
    d10.value,
    d11.value,
    d12.value,
    d13.value,
    d14.value,
    d15.value,
    d16.value,
    p1.value,
    p2.value,
    e1.value,
    e2.value
FROM t_datasets 
LEFT JOIN t_analogs AS a1 ON (t_datasets.id = a1.dataset AND a1.type = 'analog1' AND a1.frame = 'frame1')
LEFT JOIN t_analogs AS a2 ON (t_datasets.id = a2.dataset AND a2.type = 'analog2' AND a2.frame = 'frame1')
LEFT JOIN t_analogs AS a3 ON (t_datasets.id = a3.dataset AND a3.type = 'analog3' AND a3.frame = 'frame1')
LEFT JOIN t_analogs AS a4 ON (t_datasets.id = a4.dataset AND a4.type = 'analog4' AND a4.frame = 'frame1')
LEFT JOIN t_analogs AS a5 ON (t_datasets.id = a5.dataset AND a5.type = 'analog5' AND a5.frame = 'frame1')
LEFT JOIN t_analogs AS a6 ON (t_datasets.id = a6.dataset AND a6.type = 'analog6' AND a6.frame = 'frame1')
LEFT JOIN t_analogs AS a7 ON (t_datasets.id = a7.dataset AND a7.type = 'analog7' AND a7.frame = 'frame1')
LEFT JOIN t_analogs AS a8 ON (t_datasets.id = a8.dataset AND a8.type = 'analog8' AND a8.frame = 'frame1')
LEFT JOIN t_analogs AS a9 ON (t_datasets.id = a9.dataset AND a9.type = 'analog9' AND a9.frame = 'frame1')
LEFT JOIN t_analogs AS a10 ON (t_datasets.id = a10.dataset AND a10.type = 'analog10' AND a10.frame = 'frame1')
LEFT JOIN t_analogs AS a11 ON (t_datasets.id = a11.dataset AND a11.type = 'analog11' AND a11.frame = 'frame1')
LEFT JOIN t_analogs AS a12 ON (t_datasets.id = a12.dataset AND a12.type = 'analog12' AND a12.frame = 'frame1')
LEFT JOIN t_analogs AS a13 ON (t_datasets.id = a13.dataset AND a13.type = 'analog13' AND a13.frame = 'frame1')
LEFT JOIN t_analogs AS a14 ON (t_datasets.id = a14.dataset AND a14.type = 'analog14' AND a14.frame = 'frame1')
LEFT JOIN t_analogs AS a15 ON (t_datasets.id = a15.dataset AND a15.type = 'analog15' AND a15.frame = 'frame1')
LEFT JOIN t_analogs AS a16 ON (t_datasets.id = a16.dataset AND a16.type = 'analog16' AND a16.frame = 'frame1')
LEFT JOIN t_digitals AS d1 ON (t_datasets.id = d1.dataset AND d1.type = 'digital1' AND d1.frame = 'frame1')
LEFT JOIN t_digitals AS d2 ON (t_datasets.id = d2.dataset AND d2.type = 'digital2' AND d2.frame = 'frame1')
LEFT JOIN t_digitals AS d3 ON (t_datasets.id = d3.dataset AND d3.type = 'digital3' AND d3.frame = 'frame1')
LEFT JOIN t_digitals AS d4 ON (t_datasets.id = d4.dataset AND d4.type = 'digital4' AND d4.frame = 'frame1')
LEFT JOIN t_digitals AS d5 ON (t_datasets.id = d5.dataset AND d5.type = 'digital5' AND d5.frame = 'frame1')
LEFT JOIN t_digitals AS d6 ON (t_datasets.id = d6.dataset AND d6.type = 'digital6' AND d6.frame = 'frame1')
LEFT JOIN t_digitals AS d7 ON (t_datasets.id = d7.dataset AND d7.type = 'digital7' AND d7.frame = 'frame1')
LEFT JOIN t_digitals AS d8 ON (t_datasets.id = d8.dataset AND d8.type = 'digital8' AND d8.frame = 'frame1')
LEFT JOIN t_digitals AS d9 ON (t_datasets.id = d9.dataset AND d9.type = 'digital9' AND d9.frame = 'frame1')
LEFT JOIN t_digitals AS d10 ON (t_datasets.id = d10.dataset AND d10.type = 'digital10' AND d10.frame = 'frame1')
LEFT JOIN t_digitals AS d11 ON (t_datasets.id = d11.dataset AND d11.type = 'digital11' AND d11.frame = 'frame1')
LEFT JOIN t_digitals AS d12 ON (t_datasets.id = d12.dataset AND d12.type = 'digital12' AND d12.frame = 'frame1')
LEFT JOIN t_digitals AS d13 ON (t_datasets.id = d13.dataset AND d13.type = 'digital13' AND d13.frame = 'frame1')
LEFT JOIN t_digitals AS d14 ON (t_datasets.id = d14.dataset AND d14.type = 'digital14' AND d14.frame = 'frame1')
LEFT JOIN t_digitals AS d15 ON (t_datasets.id = d15.dataset AND d15.type = 'digital15' AND d15.frame = 'frame1')
LEFT JOIN t_digitals AS d16 ON (t_datasets.id = d16.dataset AND d16.type = 'digital16' AND d16.frame = 'frame1')
LEFT JOIN t_powers AS p1 ON (t_datasets.id = p1.dataset AND p1.type = 'power1' AND p1.frame = 'frame1')
LEFT JOIN t_powers AS p2 ON (t_datasets.id = p2.dataset AND p2.type = 'power2' AND p1.frame = 'frame1')
LEFT JOIN t_energies AS e1 ON (t_datasets.id = e1.dataset AND e1.type = 'energy1' AND e1.frame = 'frame1')
LEFT JOIN t_energies AS e2 ON (t_datasets.id = e2.dataset AND e2.type = 'energy2' AND e2.frame = 'frame1')
ORDER BY date ASC