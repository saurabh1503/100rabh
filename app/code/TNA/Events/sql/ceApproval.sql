SELECT eve_event_code event_code,
       eve_rid event_id,
       cet_rid ce_type_id,
       state_abrev state_abbreviation,
       INITCAP(sname) state_name,
       NVL(DECODE(SUBSTR(c.cet_name, 1, 7), 'DEFAULT', ces_type, c.cet_web_label), 'GEN') license_type,
       SUM(ces_hours) ce_hours,
       DECODE(fee_unit, 'PER PERSON', 1, SUM(ces_hours)) ce_units,
       license_label,
       good_standing
FROM event,
     program p,
     ce_table c,
     ce_types,
     ce_reporting_rates,
     ce_program_conf pc
WHERE DECODE(SUBSTR(eve_event_code, 9, 2), 'OL', SYSDATE, eve_start_date) BETWEEN cet_effective_date AND cet_thru_date
  AND DECODE(SUBSTR(eve_event_code, 9, 2), 'OL', SYSDATE, eve_end_date) BETWEEN cet_effective_date AND cet_thru_date
  AND p.prg_rid = cet_pro_rid
  AND p.prg_rid = pc.prg_rid(+)
  AND cet_state = regulation_abrev
  AND ces_cet_rid = cet_rid
  AND eve_program_id = p.prg_rid
  AND eve_start_date > SYSDATE - 100 /*Online courses CE processing*/
  AND eve_inc_account NOT IN ('UNIV') /*AND p.prg_code NOT IN ('RMG', 'SMT', 'RGS')*/
  AND (c.cet_web_label <> 'NA'
       OR c.cet_web_label IS NULL)
  AND (NVL(prg_ce_list_processing, 'NO') = 'NO'
       AND (SUBSTR(eve_event_code, 9, 2) IN ('OL',
                                             'WB')
            AND c.cet_name LIKE 'W%'
            OR SUBSTR(eve_event_code, 9, 2) NOT IN ('OL',
                                                    'WB')
            AND c.cet_name NOT LIKE 'W%')
       OR NVL(prg_ce_list_processing, 'NO') = 'YES'
       AND cet_name LIKE 'DEFAULT%')
GROUP BY eve_event_code,
         eve_end_date,
         eve_rid,
         state_abrev,
         DECODE(SUBSTR(c.cet_name, 1, 7), 'DEFAULT', ces_type, c.cet_web_label),
         sname,
         fee_unit,
         fee_amount,
         license_label,
         good_standing,
         cet_rid,
         eve_start_date,
         cet_thru_date