import pandas as pd
import numpy as np 
import sys 
import os
import matplotlib.pyplot as plt
import missingno as msno
import xlrd
import io
import seaborn as sns
sns.set(font=['sans-serif'])  
sns.set_style("whitegrid",{"font.sans-serif":['Microsoft JhengHei']})
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
params = sys.argv[1] 
path=r'./upload/'+params
if (os.path.splitext(path)[-1]==".csv"):
    df=pd.read_csv(path, parse_dates=True,encoding='utf-8')
elif (os.path.splitext(path)[-1]==".xlsx" or os.path.splitext(path)[-1]==".xls"):
    df = pd.read_excel(path, index_col=0)
params=params.split('.',1)
plt.rcParams['font.family']='DFKai-SB'
msno.matrix(df)   
plt.savefig('./missinginfo/'+params[0]+'/matrix.png',bbox_inches='tight')  
msno.bar(df)
plt.savefig('./missinginfo/'+params[0]+'/bar.png',bbox_inches='tight')  
msno.heatmap(df)
plt.savefig('./missinginfo/'+params[0]+'/heatmap.png',bbox_inches='tight') 

round(df.describe(),2).to_csv('./missinginfo/'+params[0]+'/describe.csv') 

a=[]
b=[]
count=0
for col in df.columns:  
    if df[col].isnull().sum()!=0:
        count=count+1
        a.append(col)
        b.append(round(df[col].isnull().sum()/len(df)*100,1))

# if(count>1):
# msno.heatmap(df)
# plt.savefig('./missinginfo/'+params[0]+'/heatmap.png',bbox_inches='tight')  

a=','.join(a)
b=','.join('%s' %id for id in b)
print(a+";"+b) 



