# Data-layer seed — CPT gallery + featured wiring (captured live from dev)
# Date: 2026-06-03 | Source: dev REST | Purpose: durability before final team_35 package + cutover (P005-WP002)
# These _nb_gallery / featured_media values live ONLY on the dev WP DB (set via REST by team_35);
# this seed makes them durable/recoverable. NOT auto-applied — reference for re-wiring if DB rebuilt.

## projects
- id 1055 (tiktrack): featured=- gallery=[]
- id 1006 (sfa): featured=859 gallery=[]
- id 49 (hagina-shel-nimrod): featured=1065 gallery=[1065,1066,1067,1068,1069,1070,1071]
- id 31 (rest-x-greenhouse): featured=1072 gallery=[1072,1073,1074,1075,1076,1077,1078,1079,1080,1081,1082,1083,1084,1100]

## services
- id 30 (teaching): featured=1036 gallery=[]
- id 27 (consulting-agro): featured=1035 gallery=[]
- id 26 (consulting-hydro): featured=1041 gallery=[]
- id 25 (nursery): featured=1037 gallery=[]
- id 24 (bcs): featured=1085 gallery=[1091,1092,1093,1094,1095,1096,1097,1098,1090,1101,1102,1103,1104,1105,1106,1107,1108]
- id 23 (hydro-greenhouse): featured=1041 gallery=[]
- id 22 (produce): featured=1035 gallery=[]

