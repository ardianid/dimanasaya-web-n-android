package wmi.appl.com;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;

import android.annotation.SuppressLint;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.provider.Settings;
import android.util.Log;
import android.widget.Toast;

public class UnderServ extends Service {
	
	private DBAdapter dba=null;
	private SQLiteDatabase db = null;
	
	private static String url= "act_act.php";
	private static String url_down= "get_andopengguna.php";
	
//	private Handler handler = null;
	public LocationManager mlocManager;
	float longi;
	float loti;
	float longi_now;
	float loti_now;
	
	String jnismonitor;
	Double DistOf;
	String snama;
	String stelp;
	
	Integer sceksms;
	Integer scektelp;
	
	Double hsil_calc_coord=0.0;
	
	private Handler handler=new Handler();
	
 //  private Context ctx;
	
	@Override
    public IBinder onBind(Intent intent) {
        // TODO: Return the communication channel to the service.
        //throw new UnsupportedOperationException("Not yet implemented");
		return null;
    }
	
	 @Override
	 public int onStartCommand(Intent intent, int flags, int startId) {
	         // We want this service to continue running until it is explicitly
	         // stopped, so return sticky.
	         // Toast.makeText(this, "Service Started", Toast.LENGTH_LONG).show();
	     
		 	Toast.makeText(this, "The new Service was onStartCommand", Toast.LENGTH_LONG).show();
		 	
		 	OnStartUp();
		 	
		 	//new upload_sync().execute();
		 	
	         return START_STICKY;
	 }
	 
	 
    @Override
    public void onCreate() {
    	
    //	ctx = this;
        Toast.makeText(this, "The new Service was Created", Toast.LENGTH_LONG).show();
        
    }
 
    @Override
    public void onStart(Intent intent, int startId) {
        // For time consuming an long tasks you can launch a new thread here...
        Toast.makeText(this, " Service Started", Toast.LENGTH_LONG).show();
 
    }
	
    private void OnStartUp(){
    	
    	dba = new DBAdapter(this);
    	
    	
    	// 	gps
    	
    	mlocManager = (LocationManager)getSystemService(Context.LOCATION_SERVICE);
		
    	if ( !mlocManager.isProviderEnabled( LocationManager.GPS_PROVIDER ) ){
    		turnGPSOn();
    	}
    	
    		
		Criteria criteria = new Criteria();
		
		criteria.setPowerRequirement(Criteria.POWER_LOW);
		criteria.setAccuracy(Criteria.ACCURACY_FINE);
		criteria.setAltitudeRequired(false);
	    criteria.setSpeedRequired(false);
	    criteria.setBearingRequired(false);
		criteria.setCostAllowed(true);
		
		String provider = mlocManager.getBestProvider(criteria, true);
		Location location = mlocManager.getLastKnownLocation(provider);
        
		snama="";
		stelp="";
		loti= Float.valueOf(0);
		longi= Float.valueOf(0);
		jnismonitor="";
		DistOf=0.0;
		
		updatenewlocation(location);
		mlocManager.requestLocationUpdates(provider,2000, 10, mlocListener);
		
		// akhir gps
		
		
		 String sql="select _id as noid,jnismonitor,jrakref,namapemilik,notelp,ceksms,cektelp from utility";
		 db = dba.getReadableDatabase();
	       
	       Cursor cursor = dba.SelectData(db,sql);
	       cursor.moveToFirst();
	       int jml_baris = cursor.getCount();
			
		    if (jml_baris >= 1) {
		    	
		    	snama=cursor.getString(cursor.getColumnIndex("namapemilik"));
		    	
		    	//loti=loti_now; //Float.valueOf(cursor.getString(cursor.getColumnIndex("ls_loti")));
				//longi=longi_now;  //Float.valueOf(cursor.getString(cursor.getColumnIndex("ls_longi")));
				
		    	String namapemilik = cursor.getString(cursor.getColumnIndex("noid"));
		    	
		    	jnismonitor=cursor.getString(cursor.getColumnIndex("jnismonitor"));
		    	DistOf=Double.valueOf( cursor.getString(cursor.getColumnIndex("jrakref")));
		    	stelp= cursor.getString(cursor.getColumnIndex("notelp"));
		    	
		    	sceksms=Integer.valueOf( cursor.getString(cursor.getColumnIndex("ceksms")));
		    	scektelp=Integer.valueOf( cursor.getString(cursor.getColumnIndex("cektelp")));
		    	
		    //	Toast.makeText(getBaseContext(), "cek data", Toast.LENGTH_LONG).show();
		    	
		    	
		    	if (namapemilik.trim().length() >=1 ){
		    		
		    		if(jnismonitor.equals("Jarak")) {
		    			
		    		//	Toast.makeText(getBaseContext(), "jarak", Toast.LENGTH_LONG).show();
		    			
		    			do_activity();
		    		}else{
		    			
		    			if (DistOf > 0.0){
				    		DistOf= DistOf * Double.valueOf(60000);
				    	}
		    			
		    			
		    		//	Toast.makeText(getBaseContext(), DistOf.toString(), Toast.LENGTH_LONG).show();
		    			
		    			//handler = new Handler();
		    			do_activity_minute();
		    			
		    		}
		    			
		    			
		    	}else{
		    		Intent myIntent = new Intent(getBaseContext(),  Csettings.class);
		    		startActivity(myIntent); 
		    	}
		    	
		    }else{
		    	Intent myIntent = new Intent(getBaseContext(),  Csettings.class);
	    		startActivity(myIntent); 
		    }  
		
    	
    }
    
    private double hitungJarak(double lon1, double lon2, double lat1, double lat2){   
        return  
            (  
                    (  
                        (Math.acos(Math.sin(lat1 * Math.PI / 180) *   
                        Math.sin(lat2 * Math.PI / 180) +   
                        Math.cos(lat1 * Math.PI / 180) *   
                        Math.cos(lat2 * Math.PI / 180) *   
                        Math.cos((lon1 - lon2) * Math.PI / 180)) *   
                        180 / Math.PI) * 60 * 1.1515  
                    ) *  1.609344  
            );  
    }
    
	 private void updatenewlocation(Location location){
			
			if (location != null){

				loti_now=(float) location.getLatitude();
		        longi_now=(float) location.getLongitude();  
		        
		        if (snama.trim().length() >=1 ){
		        	if (jnismonitor.equals("Jarak")) {
		        		do_activity();
		        	}
		        }
		        
			}else {
				loti_now= Float.valueOf(0);
				longi_now= Float.valueOf(0);
				Log.v("loc", "nothing location");

			}
			
			
			
		} // end of update ne location
	
	 private final LocationListener mlocListener = new LocationListener() {

			public void onStatusChanged(String provider, int status, Bundle extras) {
				// TODO Auto-generated method stub
				
				/* if(status == 
	       	            LocationProvider.TEMPORARILY_UNAVAILABLE) { 
	       	                                    Toast.makeText(getApplicationContext(), 
	       	            "LocationProvider.TEMPORARILY_UNAVAILABLE", 
	       	            Toast.LENGTH_SHORT).show(); 
	       	                        } 
	       	                        else if(status== LocationProvider.OUT_OF_SERVICE) { 
	       	                                    Toast.makeText(getApplicationContext(), 
	       	            "LocationProvider.OUT_OF_SERVICE", Toast.LENGTH_SHORT).show(); 
	       	                        } */
				
			}
			
		public void onProviderEnabled(String provider) {
				// TODO Auto-generated method stub
				//Toast.makeText( getApplicationContext(), "Gps Aktif", Toast.LENGTH_SHORT).show();
			}
			
			public void onProviderDisabled(String provider) {
				// TODO Auto-generated method stub
				
				//Toast.makeText( getApplicationContext(), "Gps Tidak Aktif,akan diaktifkan kembali", Toast.LENGTH_SHORT ).show();
	            
				turnGPSOn();
				
	            updatenewlocation(null);
				
			}
			
			public void onLocationChanged(Location location) {
				
				// TODO Auto-generated method stub
				updatenewlocation(location);
				
			}
		}; // end of mloclistener
	 
	private void turnGPSOn(){
		
		Intent intent2=new Intent("android.location.GPS_ENABLED_CHANGE");
		intent2.putExtra("enabled", true);
		sendBroadcast(intent2);
		
     String provider = Settings.Secure.getString(getContentResolver(), Settings.Secure.LOCATION_PROVIDERS_ALLOWED);
     if(!provider.contains("gps")){
         final Intent intent = new Intent();
         intent.setClassName("com.android.settings", "com.android.settings.widget.SettingsAppWidgetProvider");
         intent.addCategory(Intent.CATEGORY_ALTERNATIVE);
         intent.setData(Uri.parse("3"));
         sendBroadcast(intent);
     }
 }
	
	private void do_activity_minute(){
		
		Integer xhandle = DistOf.intValue() ;
		
		handler.postDelayed(runnable, xhandle);		
	}
	
	private Runnable runnable = new Runnable() {
		   @Override
		   public void run() {
		      
			   if (snama.trim().length() >=1 ){
		        	if (jnismonitor.equals("Waktu")) { 
			   
		        			new upload_sync().execute();
		        			
		        	}
			   }
			  
			  Integer xhandle = DistOf.intValue() ;
		      handler.postDelayed(this, xhandle);
		   }
		};
	
	@SuppressLint("SimpleDateFormat")
	private void upload_data(){
		
		try {
			
		String kjarak="0";
		String kwaktu="0";
		
		if (jnismonitor.equals("Jarak")){
			kjarak = String.valueOf(DistOf);
		}else{
			kwaktu = String.valueOf(DistOf);
		}
		
		Date date= new Date();
		DateFormat jam=new SimpleDateFormat("HH:mm:ss");
		DateFormat tanggalsekarang= new SimpleDateFormat("dd/MM/yyyy");
		
		String jambukti = jam.format(date);
		String tglbukti = tanggalsekarang.format(date);
		
			ArrayList<NameValuePair> postparam = new ArrayList<NameValuePair>();
			postparam.add(new BasicNameValuePair("nama", snama));
			postparam.add(new BasicNameValuePair("longi", String.valueOf (longi_now)));
			postparam.add(new BasicNameValuePair("loti", String.valueOf(loti_now)));
			postparam.add(new BasicNameValuePair("telp", String.valueOf(stelp)));
			postparam.add(new BasicNameValuePair("jnismonitor", String.valueOf(jnismonitor)));
			postparam.add(new BasicNameValuePair("jarak", String.valueOf(kjarak)));
			postparam.add(new BasicNameValuePair("waktu", String.valueOf(kwaktu)));
			postparam.add(new BasicNameValuePair("ceksms", String.valueOf(sceksms)));
			postparam.add(new BasicNameValuePair("cektelp", String.valueOf(scektelp)));
			postparam.add(new BasicNameValuePair("tanggal", String.valueOf(scektelp)));
			postparam.add(new BasicNameValuePair("tanggal",tglbukti));
			postparam.add(new BasicNameValuePair("jam",jambukti));
			
		//	Log.v("longlat", longi_now + "," + loti_now);
			
			String res = CustomHttpClient.executeHttpPost(url, postparam);
			 
			 if (res.toString().trim().equals("1")){
				 
				 longi =  longi_now;
				 loti = loti_now;
				 
				 Log.v("log upload", "ok");
			 } else if (res.toString().trim().equals("2")) {
				 
				 download_data();
				 
			 }else {
				 Log.v("log upload", "error");
				 //jmler++; // Toast.makeText(getApplicationContext(), "Data gagal dikirim",Toast.LENGTH_SHORT).show();
			 }
			
		} catch (Exception e) {
			// TODO: handle exception
			Log.v("log upload",e.toString());
		}
		
		
	}
	
	private void do_activity(){
		
		if (loti== Float.valueOf(0) || longi== Float.valueOf(0) ) {
			
			loti = loti_now;
			longi = longi_now;
			
			new upload_sync().execute();
			
		}else {
		
		Double hasil = hitungJarak(longi, longi_now, loti, loti_now);
				hasil = hasil * 1000;
		
		if (hasil >= DistOf){
			
			new upload_sync().execute();
			
		}}
				
	}
	
	private void download_data(){
		
		try {
			
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("nama", snama));
			params.add(new BasicNameValuePair("notelp", stelp));
			
			String sresult= CustomHttpClient.getRequest(url_down, params);
			
			JSONArray jarra = new JSONArray(sresult);
			
	        String xnama="";
	        String xtelp="";
	        String xjnismonitor="";
	        String xjarak="";
	        String xwaktu="";
	        String xceksms="";
	        String xcektelp="";
	        
	        	xnama=jarra.getJSONObject(0).getString("nama_pengguna");
	        	xtelp=jarra.getJSONObject(0).getString("notelp");
	        	xjnismonitor=jarra.getJSONObject(0).getString("jnismonitor");
	        	xjarak=jarra.getJSONObject(0).getString("jarak");
	        	xwaktu=jarra.getJSONObject(0).getString("waktu");
	        	xceksms=jarra.getJSONObject(0).getString("ceksms");
	        	xcektelp=jarra.getJSONObject(0).getString("cektelp");

	        if (xnama.toString().length()>0){
	        	snama = xnama;
	        	stelp = xtelp;
	        	
	        	if (jnismonitor.equals("Waktu") && xjnismonitor.equals("Jarak") ){
	        		handler.removeCallbacks(runnable);
	        	}
	        	
	        	if(xjnismonitor.equals("Jarak")){
	        		
	        		DistOf = Double.valueOf(xjarak);
	        		
	        		jnismonitor = xjnismonitor;
		        	scektelp = Integer.valueOf(xcektelp);
		        	sceksms = Integer.valueOf(xceksms);
	        		
		        //	do_activity();
		        	
	        	}else{
	        		
	        		Double DistMe = Double.valueOf(xwaktu);
	        		
	        		if (DistMe > 0.0){
			    		DistMe= DistMe * Double.valueOf(60000);
			    	}
	        		
	        		scektelp = Integer.valueOf(xcektelp);
		        	sceksms = Integer.valueOf(xceksms);
	        		DistOf = DistMe;
	        		
	        		if (jnismonitor.equals("Jarak") && xjnismonitor.equals("Waktu") ){
	        			jnismonitor = xjnismonitor;
	        		//	handler = new Handler();
		        		do_activity_minute();
		        	}
	        		
	        		
	        	}
	        	
	        	
	        	
	        }
			
	        
		} catch (Exception e) {
			// TODO: handle exception
			 e.printStackTrace();
		}
		
	}
    
    private class upload_sync extends AsyncTask<Void,Void, String>{
    	
    	//private ProgressDialog pDialog;
    	
    	@Override
        protected void onPreExecute() {
            super.onPreExecute();
      //      pDialog = new ProgressDialog(Csettings.this);
       //     pDialog.setMessage("Mohon sabar menunggu, Proses upload sedang berlangsung...");
        //    pDialog.setIndeterminate(false);
         //   pDialog.setCancelable(true);
           // pDialog.show();
        }
    	
    	@Override
		protected void onPostExecute(String content) {
    		
    	/*	pDialog.dismiss();
    		
           if (jmler > 0) {
            	Toast.makeText(Csettings.this,jmler + 
                        " Upload data gagal...", Toast.LENGTH_SHORT).show(); 
            } else {
            	Toast.makeText(Csettings.this, 
                      "Upload data selesai...", Toast.LENGTH_SHORT).show(); 
            	
            	try {
            		upload_data();
            		download_data();

				} catch (Exception e) {
					// TODO Auto-generated catch block
					//e.printStackTrace();
					Toast.makeText(getBaseContext(), e.toString(), Toast.LENGTH_LONG).show();
				}
            	
            	
            	
            }
            
            jmler=0;
            jmlok=0; */
            
        }
    	
		@Override
		protected String doInBackground(Void... arg0) {
			// TODO Auto-generated method stub
			
			try {
				
			//	if (handler != null){
		    //		handler.removeCallbacks(runnable);
		    //	}
				
				//handler.postDelayed(runnable, 5000);
				
				//Log.v("upl", "ok");
				
				upload_data();
				//download_data();
				
			} catch (Exception e) {
				// TODO: handle exception
				Log.v("error upl noo", e.toString());
			}
			
			
			return null;
		}
    	
		
    } // akhir dari asyncTask
	
}
