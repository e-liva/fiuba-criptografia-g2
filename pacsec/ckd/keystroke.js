/* Biometric keyboard cadence library 

Usage:

var c=new cAPI(function(){return mode;});

where function is a callback to return the observation mode.
Observatin modes are:
 - training: string 'train'
 - running: string 'run'


* Observe a DOM field
   c.listen(element_id);
  where element_id is the DOM element id to observe (keyup and keydown events).

* Save the current training to a profile and start over.
   c.save(profile_name);
  where profile_name is the profile name to save

  Notes: To keep the current training just reload it.
         Any string will be accepted as a profile.
         Using save to an existent profile will overwrite it.

* Load a pre-saved trained profile to the current training.
   c.load(profile_name);
  where profile_name is the profile name to load the training. If the profile doesn't
  exist the train will be cleared.

* Start a comparison
   c.run(function(text){console.log(text);});
  where function will recieve a text to display. In training mode the function does nothing but
  return a "training mode" text. In running mode it returns the result of the previously
  observed cadence while in running mode compared to the current training.

* Find the profile that matches best
   c.match(function(text){console.log(text);});
  where function will recieve a text to display. In training mode the function does nothing but
  return a "training mode" text. In running mode it returns the profile that matches best with
  the result of the previously observed cadence while in running mode.
  The current training is ignored.

	Note: If you want the current trining to be compared just save it as a special profile
        e.g. "Current training"


* Showing the current training
   c.drawtrain(element_id);
  Where element_id is a DOM element id where the contents will be replaced with a table with
  the current training.

Dependencies: jQuery.


*/

function cAPI(attr_mode) {
	var
		statistic={
			total:function(arr) {
				var sum=0,i,l=arr.length;
				for (i=0;i<l;i++) {
					sum+=arr[i];
				}
				return sum;
			},
			sqtotal:function(arr) {
				var sum=0,i,l=arr.length;
				for (i=0;i<l;i++) {
					sum+=arr[i]*arr[i];
				}
				return sum;
			},
			mean:function(arr) {
				return this.total(arr)/arr.length;
			},
			variance:function(arr) {
				var
					mean=this.mean(arr),
					sum=0,
					i,
					l=arr.length,
					d;
				for (i=0;i<l;i++) {
					d=arr[i]-mean;
					sum+=d*d;
				}
				return sum/l;
			}
		},
		self=this,
		attr_last={key:"",timeStamp:0};

	//Semi-public attributes
	this.listening=[];
	this.data={}
	this.trained={}
	this.profiles={};

	function triggerEvent(key,timeStamp) {
		var
			last2={key:key,timeStamp:timeStamp},
			last=attr_last,
			diff={
				k1:last.key,
				k2:last2.key,
				delta:last2.timeStamp-last.timeStamp
			};
		attr_last=last2;
		if (diff.key!="" && diff.delta<1000) {
			if (attr_mode()=='train') {
				process(diff,self.trained);
			} else {
				process(diff,self.data);
			}
		}
	}

	function clearForm() {
		for (var i in self.listening) {
			self.listening[i]['val']('');
		}
		if (self.listening[0]) self.listening[0].focus();
	}
	function calculatePercent(ammount) {
		var 
			cls=ammount<0.15?'result-cad p-low':(ammount<0.2?'result-cad p-medium':'result-cad p-urgent'),
			//text=(Math.round(10000*(1-ammount))/100),
			text=ammount<0.15?'Accepted':(ammount<0.2?'In doubt':'Denied');
		return '<span class="'+cls+'">'+(Math.round((1-ammount)*100))+'% ('+text+")</span>";
	}

	//Public methods
	this['listen']=function(id) {
		var $el=$('#'+id);
		this.listening.push($el);
		$el['keydown'](function(e){
			triggerEvent(e.keyCode+'d',e.timeStamp);
		});
		$el['keyup'](function(e){
			triggerEvent(e.keyCode+'u',e.timeStamp);
		});
	}
	this['save']=function(profile) {
		this.profiles[profile]=this.trained;
		this.trained={};
	}
	this['load']=function(profile) {
		this.trained=this.profiles[profile];
		if (this.trained==undefined) this.trained={};
	}
	this['run']=function(callback) {
		if (attr_mode()=='train') {
			callback("Entrenando... Haga click en modo <span class='label label-info'>RUN</span> para probar");
		} else if (this.trained) {
			callback(textCompare());
			this.data={};
		} else {
			callback("Please train first");
		}
		clearForm();
	}
	this['match']=function(callback) {
		if (attr_mode()=='train') {
			callback("Entrenando... Haga click en modo <span class='label label-info'>RUN</span> para probar");
		} else {
			var winner=null,ammount=200;
			for (var profile in this.profiles) {
				var
					cmp=compare(this.data,this.profiles[profile]);
				if (cmp!=undefined && cmp<ammount) {
					winner=profile;
					ammount=cmp;
				}
			}
			if (winner) {
 				callback('And the winner is '+winner+' with a rate of '+calculatePercent(ammount)+'.');
			} else {
				callback('Please do a little bit more training!');
			}

			this.data={};
		}
		clearForm();
	}
	this['drawtrain']=function(id) {
		var $t=$('#'+id),out='<table class="table table-striped"><tr><th>k1_k2</th><th>deltas</th><th>n</th><th>&mu;</th><th>&sigma;<sup>2</sup></th><th>expected val</th></tr>',empty=true;
		for (var i in this.trained) {
			var
				train=this.trained[i],
				mean=statistic.mean(train),
				variance=statistic.variance(train)+1,
				gamma=new Gamma(mean*mean/variance,variance/mean),
				expected=gamma.cdf(mean);
			empty=false;
			out+='<tr><td>'+i+'</td><td>'+train.join(', ')+'</td><td>'+train.length+'</td><td>'+mean+'</td><td>'+variance+'</td><td>'+expected+'</td></tr>';
		}
		out+='</tr></table>';
		if (empty) out='<p>Please train it first</p>';
		$t['html'](out+"<p>"+this['getTrain']().join(', ')+"</p>");
	}

	//Private methods	
	function process(data,arr) {
		var key=data.k1+'_'+data.k2;
		if (arr[key]!=undefined) {
			arr[key].push(data.delta);
			return;
		}
		arr[key]=[data.delta];
	}
	function compare(arr_data,arr_trained) {
		var result=0,total=0;
		for (var i in arr_data) {
			var
				data=arr_data[i],
				train=arr_trained[i];
			if (train) {
				var
					mean=statistic.mean(train),
					variance=statistic.variance(train)+1, //just to add more error and avoid sigma=0
					gamma=new Gamma(mean*mean/variance,variance/mean),
					expected=gamma.cdf(mean);
				for (var j in data) {
					var d=gamma.cdf(data[j])-expected;
					result+=d*d;
					total++;
				}
			}
		}
		if (!total) return undefined;
		return result/total;
	}

	function textCompare(){
		var r=compare(self.data,self.trained);
		if (r==undefined) return "No se entrenó lo suficiente";
		return "Result: "+calculatePercent(r);
	}

	this['getTrain']=function() {
		var out=[];
		for (var i in self.trained) {
			var
				train=self.trained[i];
			if (train) {
				out.push(i);
				out.push(statistic.total(train));
				out.push(statistic.sqtotal(train));
				out.push(train.length);
			}
		}
		return out;
	}










/************************\
* Gamma distribution API *
\************************/


	function Gamma(attr_a,attr_b) {
		var log=Math.log,exp=Math.exp,abs=Math.abs,sqrt=Math.sqrt;

		function LogGamma(Z) {
			var S=1+76.18009173/Z-86.50532033/(Z+1)+24.01409822/(Z+2)-1.231739516/(Z+3)+.00120858003/(Z+4)-.00000536382/(Z+5);
			return (Z-.5)*log(Z+4.5)-(Z+4.5)+log(S*2.50662827465);
		}
		
		function Gcf(X,A) {        // Good for X>A+1
				var A0=0;
				var B0=1;
				var A1=1;
				var B1=X;
				var AOLD=0;
				var N=0;
				while (abs((A1-AOLD)/A1)>.00001) {
					AOLD=A1;
					N=N+1;
					A0=A1+(N-A)*A0;
					B0=B1+(N-A)*B0;
					A1=X*A0+N*A1;
					B1=X*B0+N*B1;
					A0=A0/B1;
					B0=B0/B1;
					A1=A1/B1;
					B1=1;
				}
				var Prob=exp(A*log(X)-X-LogGamma(A))*A1;
			return 1-Prob
		}
		
		function Gser(X,A) {        // Good for X<A+1.
				var T9=1/A;
				var G=T9;
				var I=1;
				while (T9>G*.00001) {
					T9=T9*X/(A+I);
					G=G+T9;
					I=I+1;
				}
				G=G*exp(A*log(X)-X-LogGamma(A));
		    return G
		}
		
		function normalcdf(X){   //HASTINGS.  MAX ERROR = .000001
			var T=1/(1+.2316419*abs(X));
			var D=.3989423*exp(-X*X/2);
			var Prob=D*T*(.3193815+T*(-.3565638+T*(1.781478+T*(-1.821256+T*1.330274))));
			if (X>0) {
				Prob=1-Prob
			}
			return Prob
		}
		
		function Gammacdf(x,a) {
			var GI,z,y,b1,phiz,w,b2,u;
			if (x<=0) {
				GI=0
			} else if (a>200) {
				z=(x-a)/sqrt(a)
				y=normalcdf(z)
				b1=2/sqrt(a)
				phiz=.39894228*exp(-z*z/2)
				w=y-b1*(z*z-1)*phiz/6  //Edgeworth1
				b2=6/a
				u=3*b2*(z*z-3)+b1*b1*(z^4-10*z*z+15)
				GI=w-phiz*z*u/72        //Edgeworth2
			} else if (x<a+1) {
				GI=Gser(x,a)
			} else {
				GI=Gcf(x,a)
			}
			return GI
		}
		
		function compute(X,A,B) {
			return Gammacdf(X/B,A/1)
		}
	
		this.cdf=function(x) {
			return compute(x,attr_a,attr_b);
		}
	}
}

window['cAPI']=cAPI;
