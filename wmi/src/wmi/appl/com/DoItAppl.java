package wmi.appl.com;

import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;

public class DoItAppl extends Activity {

	private DBAdapter dba=null;
	private SQLiteDatabase db = null; 
	
	Integer Snoid;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		dba = new DBAdapter(this);
		
		 String sql="select _id as noid from utility";
		db = dba.getReadableDatabase();
	       
	       Cursor cursor = dba.SelectData(db,sql);
	       cursor.moveToFirst();
	       int jml_baris = cursor.getCount();
			
		    if (jml_baris >= 1) {
		    	
		    	Snoid=Integer.valueOf(cursor.getString(cursor.getColumnIndex("noid")));
		    	
		    	if (Snoid==0){
		    		Intent myIntent = new Intent(getBaseContext(),  WmiActivity.class);
		    		startActivity(myIntent); 
		    	}else{
		    		startService(new Intent(getBaseContext(), UnderServ.class));
		    	}
		    	
		    }else{
		    	Intent myIntent = new Intent(getBaseContext(),  WmiActivity.class);
	    		startActivity(myIntent); 
		    } 
	       
		
	} // akhir dari oncreate
	
}
