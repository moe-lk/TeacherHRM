use MOENational
SELECT A1.[ID] ,
A1.[CenCode] AS school_id ,
A1.[SubCode] AS subject_id ,
A1.[SecCode] ,A1.[Medium] ,
A1.[AvailableTch] ,
A2.[ApprCardre] ,
E1.[ExcDef] AS excess_dificit
INTO #tempcardre1$NIC
FROM AvailableTeachers AS A1
INNER JOIN ExcessDeficit AS E1
ON A1.SubCode = E1.SubCode
INNER JOIN ApprovedCardre AS A2 ON A1.SubCode = A2.SubCode
WHERE A1.SecCode = '2'
AND A2.Medium = '1'

SELECT * FROM #tempcardre1$NIC 

DROP Table #tempcardre1$NIC

SELECT * FROM 
( SELECT 
CenCode, 
SecCode, 
SubCode,
SubCode+'(1)' As subject_id1, 
SubCode+'(2)' As subject_id2, 
Deficit as availableTch, 
Excess as apprcardre, 
ExcDef 
FROM ExcessDeficit ) AS P 
PIVOT ( SUM(apprcardre) FOR 
SubCode IN ( [201], [204], [203] ) ) AS pv1 
PIVOT ( SUM(availableTch) FOR 
subject_id1 IN ( [2011], [2041], [2031] ) ) AS pv2 
PIVOT ( SUM(ExcDef) 
FOR subject_id2 IN ( [2012], [2042], [2032] ) ) AS pv3

SELECT * FROM 
( SELECT 
CenCode, 
--SecCode, 
SubCode,
SubCode+'(1)' As subject_id1, 
SubCode+'(2)' As subject_id2, 
Deficit as availableTch, 
Excess as apprcardre, 
ExcDef 
FROM ExcessDeficit ) AS P 
PIVOT ( SUM(apprcardre) FOR 
SubCode IN (  [201] , [202] , [203] , [204] , [205] , [206] , [207] , [208] , [209] , [210] , [211] , [212] , [213] , [214] , [215] , [216] , [217] , [218] , [219] , [220] , [221] , [222] , [223] , [224] , [225] , [226] , [227] , [228] , [229] , [230] , [231] , [232] , [233] , [234] , [235] , [236] , [237] , [238] , [239] , [240] , [241] , [242] , [243] , [244] , [245] , [246] , [247] , [248] , [249] , [250] , [251] )
 ) AS pv1
 PIVOT
 (
    SUM(availableTch) FOR subject_id1 IN ( [2011] , [2021] , [2031] , [2041] , [2051] , [2061] , [2071] , [2081] , [2091] , [2101] , [2111] , [2121] , [2131] , [2141] , [2151] , [2161] , [2171] , [2181] , [2191] , [2201] , [2211] , [2221] , [2231] , [2241] , [2251] , [2261] , [2271] , [2281] , [2291] , [2301] , [2311] , [2321] , [2331] , [2341] , [2351] , [2361] , [2371] , [2381] , [2391] , [2401] , [2411] , [2421] , [2431] , [2441] , [2451] , [2461] , [2471] , [2481] , [2491] , [2501] , [2511] )
 ) AS pv2
 PIVOT
 (
    SUM(excdef) FOR subject_id2 IN ( [2012] , [2022] , [2032] , [2042] , [2052] , [2062] , [2072] , [2082] , [2092] , [2102] , [2112] , [2122] , [2132] , [2142] , [2152] , [2162] , [2172] , [2182] , [2192] , [2202] , [2212] , [2222] , [2232] , [2242] , [2252] , [2262] , [2272] , [2282] , [2292] , [2302] , [2312] , [2322] , [2332] , [2342] , [2352] , [2362] , [2372] , [2382] , [2392] , [2402] , [2412] , [2422] , [2432] , [2442] , [2452] , [2462] , [2472] , [2482] , [2492] , [2502] , [2512] )
 ) AS pv3
