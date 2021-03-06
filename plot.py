import numpy as np
import pandas as pd 
import seaborn as sns
sns.set(font=['sans-serif'])  
sns.set_style("whitegrid",{"font.sans-serif":['Microsoft JhengHei']})
import matplotlib.pyplot as plt
import sys 
import datetime
from pathlib import Path
import random
import io
import matplotlib.ticker as ticker 
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
params=sys.argv[1] 
params=params.split(';')
params=params[:-1]

file=params[0]
var=params[1]

methods=params[2]
methods=methods.split(',')

count=params[3]


vp=params[4]
vp=vp.split(',')
ycol=params[5]


path=r'./upload/'+file
df=pd.read_csv(path, parse_dates=True,encoding='utf-8')
name=file.split('.',1) 
plt.rcParams["axes.labelsize"] = 24 
plt.rcParams['axes.unicode_minus']=False
def barplot(method,im_df,var): 
    plt.figure()
    g=sns.factorplot(var,data=im_df,aspect=2,kind="count",color="steelblue")
    if len(np.unique(im_df[var]))>=20:
        g.set_xticklabels(step=10,rotation=35)
    plt.title(enmethoden(method),fontsize=24)
    plt.ScalarFormatter()
    plt.savefig('./imputation_photo/'+name[0]+'/'+count+var+'_'+method+'_factor.png',bbox_inches='tight',facecolor="w" )
def cabarplot(method,im_df,var): 
    plt.figure(figsize=(12,12))
    plt.title(enmethoden(method),fontsize=24)
    sns.countplot(x=var, data=im_df)

    ax = plt.gca()     
    for p in ax.patches:
            ax.text(p.get_x() + p.get_width()/2., p.get_height(), '%d' % int(p.get_height()), 
                    fontsize=12, color='red', ha='center', va='bottom')
    plt.savefig('./imputation_photo/'+name[0]+'/'+count+var+'_'+method+'_cabar.png',bbox_inches='tight',facecolor="w" )
def pieplot(method,im_df,var): 
    plt.figure() 
    plt.title(enmethoden(method),fontsize=24)
    dfp=im_df[var].value_counts()
    plt.pie(dfp.values[:5], labels=dfp.index.values[:5],autopct='%1.1f%%', shadow=True)
    plt.savefig('./imputation_photo/'+name[0]+'/'+count+var+'_'+method+'_pie.png',bbox_inches='tight',facecolor="w" )
def boxplot(method,im_df,var): 
    plt.figure(figsize = (5,10))
    g=sns.boxplot(y=im_df[var].dropna(),width=.2)
    plt.title(enmethoden(method),fontsize=24) 
    plt.savefig('./imputation_photo/'+name[0]+'/'+count+var+'_'+method+'_box.png',bbox_inches='tight',facecolor="w" )
def Og_jointplot(method,df,var,y_col):
    graph=sns.jointplot(var, y_col, data=df, kind="reg",color="b")
    plt.subplots_adjust(top=0.9)
    graph.fig.suptitle(enmethoden(method),fontsize=24)
    graph.set_axis_labels(var, y_col, fontsize=24)
    plt.savefig('./imputation_photo/'+name[0]+'/'+count+var+'_'+method+'_joint.png',bbox_inches='tight',facecolor="w" )
def jointplot(method,df,im_df,var,y_col): 
    list = ['g', 'r', 'c', 'm', 'y', 'k'] 
    randomlist = random.sample(list, 1) 
    a=[]
    b=[]
    n=[]
    s=[] 
    for i in range(len(df)):
        if df[var].iloc[i]==im_df[var].iloc[i]:
            a.append(df[var].iloc[i])
            b.append(df[y_col].iloc[i])
        else:
            n.append(im_df[var].iloc[i])
            s.append(im_df[y_col].iloc[i])    
    graph=sns.jointplot(a,b, data=df, kind="reg",color='b')
    graph.x=n
    graph.y=s
    graph.plot_joint(plt.scatter,marker='o',alpha=1,color=randomlist[0])
    plt.subplots_adjust(top=0.9)
    graph.fig.suptitle(enmethoden(method), fontsize=24)
    graph.set_axis_labels(var, y_col, fontsize=24)    
    plt.savefig('./imputation_photo/'+name[0]+'/'+count+var+'_'+method+'_joint.png',bbox_inches='tight',facecolor="w" )
def enmethoden(methods):
    if(methods=="mean"):
        return methods.replace("mean","平均值")
    elif(methods=="mode"):
        return methods.replace("mode","眾值")
    elif(methods=="del"):
        return methods.replace("del","列表刪除")
    elif(methods=="delrow"):
        return methods.replace("delrow","欄位刪除")
    elif(methods=="knn"):
        return methods.replace("knn","最近鄰居法")
    elif(methods=="linear"):
        return methods.replace("linear","線性迴歸法")
    elif(methods=="logistic"):
        return methods.replace("logistic","邏輯迴歸法")
    elif(methods=="mice"):
        return methods.replace("mice","多重插補法")
    else:
        return methods.replace("first","原始資料")

for method in methods:
    im_path=r'./download/'+count+var+method+'_'+file
    im_df=pd.read_csv(im_path)
    for x in vp:      
        if(x=='bar'):  
            barplot("first",df,var)
            barplot(method,im_df,var)
        elif(x=='cabar'):  
            cabarplot("first",df,var)
            cabarplot(method,im_df,var)
        elif(x=='pie'):  
            pieplot("first",df,var)
            pieplot(method,im_df,var)
        elif(x=='box'):
            boxplot("first",df,var)
            boxplot(method,im_df,var)
        elif(x=='joint'):
            Og_jointplot("first",df,var,ycol)
            jointplot(method,df,im_df,var,ycol)



