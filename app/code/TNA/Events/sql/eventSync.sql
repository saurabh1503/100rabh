SELECT DISTINCT
  P.PRG_DES,
  P.PRG_CODE,
  P.PRG_DESCRIPTION,
  P.PRG_ONLINE_DESCRIPTION,
  P.DES_DESIGNATION_TYPE DESIGNATION_TYPE,
  P.DES_COMMENTS DES_COMMENTS,
  E.EVE_RID,
  E.EVE_EVENT_CODE || DECODE (P.DES_DESIGNATION_TYPE, 'PRODSCH', DECODE (NUM,  1, '.1000',  2, '.1500',  3, '.2500'), '') EVE_EVENT_CODE,
  E.EVE_FORMAL_DATE || DECODE(TRIM(AGD_START_TIME), '930', '%MORNING', '1330', '%AFTERNOON', '1530', '%AFTERNOON', '1730', '%EVENING', '') EVE_FORMAL_DATE,
  E.EVE_START_DATE,
  E.EVE_END_DATE,
  TO_BINARY_DOUBLE (E.EVE_COST * NVL2 (REGEXP_SUBSTR (eve_inc_account, '^(SS)|(UNIV)')
                                    || REGEXP_SUBSTR (eve_inc_account, '(RS)$'), 1, 0)) PRICE,
  L.LOC_NAME,
  L.LOC_ST_ADDRESS LOC_ADDRESS,
  L.LOC_ST_CITY LOC_CITY,
  L.LOC_ST_STATE LOC_STATE,
  cic3.STATE_NAMES.SNAME STATE_NAME,
  L.LOC_ST_ZIP_CODE LOC_ZIP_CODE,
  L.LOC_URL LOCATION_UNIV_URL,
  DECODE (
    E.EVE_INC_ACCOUNT,
    'UNIV', '',
    NVL (
       E.EVE_LICENSEE_URL,
       REGISTRATIONS_API_PKG.GET_LICENSEE_URL (L.LOC_ST_STATE,
                                               P.PRG_DES_URL,
                                               E.EVE_LIC_RID)))
  LICENSEE_URL,
  DECODE (
    REGISTRATIONS_API_PKG.AGENDA_EXISTS (EVE_EVENT_CODE, E.EVE_RID),
    0, 'NA',
       'https://ftp.scic.com/Chuy/DownloadFile2.aspx?File=Q:\\AGENDAS\\'
    || EVE_EVENT_CODE
    || '.pdf')
    AGENDA_URL,
  TO_CHAR (E.EVE_NUM_SEATS - NVL (r.reg_seats, 0)) SEATS_ARE_AVAILABLE,
  REGISTRATIONS_API_PKG.GET_GENERAL_INFO_HTML (E.EVE_RID)
    GENERALINFOHTML,
  REGISTRATIONS_API_PKG.GET_GATEKEEPER_TEXT (P.PRG_CODE, L.LOC_ST_STATE)
    GATE_KEEPER_HTML,
  E.eve_inc_account EVENTTYPE,
  E.EVE_HOURS EVENT_HOURS,/*   , nvl2(ECA.EVE_RID,'Y','N') CE_APPROVED  TODO  CE*/  
  E.EVE_UC_NUMBER UNIV_COURSE_NUMBER,
  DECODE (E.EVE_UC_EXAM_CREDIT,  0, 'Credit',  1, 'Exam')
    UNIV_COURSE_TYPE,
  cic3.f_eep_type (E.eve_rid, 'Professor') UNIV_PROFESSOR,
  NVL (x.zcll_radlong, y.zcll_radlong) eve_long,
  NVL (x.zcll_radlat, y.zcll_radlat) eve_lat
FROM cic3.event E,
    cic3.STATE_NAMES,
    (SELECT PRG_RID,
        PRG_DESCRIPTION,
        PRG_ONLINE_DESCRIPTION,
        DECODE (PRG_CODE, 'LE', '2LE', PRG_CODE) PRG_CODE,
        DECODE (PRG_DES_RID, 1, DECODE(PRG_RUBLE, 'YES', DECODE (PRG_CODE, 'RGS', 'RUBLE', 'RUBLE'), DECODE (PRG_REQUIRED, 'YES', 'CIC', 'RUBLE')),
          DECODE (DES_DESIGNATION_TYPE, 'DSR', 'CISR', DECODE (PRG_CODE, '12ES', 'ES','20ES', 'ES', DES_DESIGNATION_TYPE))) PRG_DES,
        DECODE (PRG_DES_RID, 1, DECODE (PRG_RUBLE, 'YES', DECODE (PRG_CODE, 'RGS', 'RGS', 'RUBLE'), DECODE (PRG_REQUIRED, 'YES', 'CIC', 'RUBLE')),
          DECODE (DES_DESIGNATION_TYPE,'DSR', 'CISR', DECODE (PRG_CODE, '12ES', 'ES', '20ES', 'ES','BPA', 'ADV', 'BPR', 'ADV', DES_DESIGNATION_TYPE)))
            PRG_DES_URL,
        DES_DESIGNATION_TYPE,
        DES_COMMENTS
      FROM cic3.PROGRAM, cic3.DESIGNATIONS
      WHERE DES_DESIGNATION_ID = PRG_DES_RID
    ) P,
     cic3.LOCATION L,
     zipcodelonglat x,
     zipcodelonglat y,
     (SELECT reg_eve_rid, COUNT (reg_cli_rid) reg_seats
          FROM cic3.registration
         WHERE reg_reg_int_hld_wait IN (1,
                                        0,
                                        2,
                                        5)
      GROUP BY reg_eve_rid) R,
     cic3.EVENT_TASK,
     cic3.EVENT_TASKLIST,
     (SELECT num
        FROM cic3.num
       WHERE num <= 3),
     (SELECT agd_eve_rid,
             FIRST_VALUE (' ' || AGD_START_TIME)
                OVER (PARTITION BY agd_eve_rid ORDER BY AGD_START_TIME)
                AGD_START_TIME,
             ROW_NUMBER ()
                OVER (PARTITION BY agd_eve_rid ORDER BY AGD_START_TIME)
                rn
        FROM cic3.agenda
       WHERE agd_sub_name = 'Join Webinar' AND agd_day = 1) webinar_start
    /*    , (select distinct eve_rid from v_EVENT_CE_APPROVAL) eca  TODO  CE*/
WHERE E.EVE_PROGRAM_ID = P.PRG_RID
     AND E.EVE_LOC_RID = L.LOC_RID
     AND E.EVE_RID = R.REG_EVE_RID(+)
     AND (E.EVE_START_DATE >= TRUNC (SYSDATE) OR E.EVE_START_DATE >= TRUNC (SYSDATE) - 30 AND E.eve_inc_account = 'UNIV')
     AND E.EVE_DATE_DELETED IS NULL
     AND E.EVE_RID = ETSK_EVE_RID(+)
     AND e.eve_rid = agd_eve_rid(+)
     AND (webinar_start.rn = 1 OR webinar_start.rn IS NULL)
     AND ETSK_ETL_RID = ETL_RID(+)
     /*   and E.EVE_RID = ECA.EVE_RID(+)  TODO  CE*/
     AND E.eve_inc_account NOT IN ('SSIH', 'LSIH')
     AND P.DES_DESIGNATION_TYPE <> 'PRODSCH'
     AND (P.DES_DESIGNATION_TYPE = 'PRODSCH' OR NUM = 1)
     AND (ETSK_DATE_DONE IS NOT NULL AND ETL_DESCCODE = 'PUBLIC')
     AND (   E.eve_inc_account NOT IN ('SS', 'SSIHOE')
          OR E.eve_cost > 0 AND E.eve_init_rsn IS NOT NULL)
     AND (   loc_st_state NOT IN ('OL')
          OR     E.EVE_END_DATE >= TRUNC (SYSDATE, 'MONTH')
             AND E.EVE_END_DATE < ADD_MONTHS (TRUNC (SYSDATE, 'MONTH'), 1))
     AND x.zcll_zipcode(+) = loc_st_zip_code
     AND y.zcll_zipcenter(+) = loc_st_zip_code
     AND cic3.STATE_NAMES.ST(+) = L.LOC_ST_STATE
