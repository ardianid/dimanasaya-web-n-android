package wmi.appl.com;

import android.content.Context;
import android.database.Cursor;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

public class DBAdapter extends SQLiteOpenHelper {
	
	private static final String DATABASE_NAME = "wmidbase";
	private static final int DATABASE_VERSION = 3;
	
	  private static final String create_tab_user = "create table user ("
			  + "[_id] INTEGER PRIMARY KEY autoincrement, "
			  + "[nama] TEXT, "
			  + "[pass] TEXT);";
	 
	  private static final String create_tab_util = "create table utility ("
			  + "[_id] INTEGER PRIMARY KEY autoincrement, "
			  + "[jrakref] REAL, "
			  + "[namapemilik] TEXT, "
			  + "[notelp] TEXT, "
			  + "[cektelp] INTEGER, "
			  + "[ceksms] INTEGER, "
			  + "[jnismonitor] TEXT);";
	  
	  public DBAdapter(Context ctx)
	  {
		  super(ctx, DATABASE_NAME, null, DATABASE_VERSION); 
	  }
	  
		  @Override
		  public void onCreate(SQLiteDatabase db) {
			  try {
				  db.execSQL(create_tab_user);
				  db.execSQL(create_tab_util);
				  
			  } catch (SQLException e) {
				  e.printStackTrace();
			  }
		  }
		  
		  @Override
		  public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion)
		  {
		  Log.w("", "Upgrade database dari versi " + oldVersion + " ke "
		  + newVersion + ", yang akan menghapus semua data lama");
		  db.execSQL("DROP TABLE IF EXISTS kontak");
		  onCreate(db);
		  }
		  
		  public Cursor SelectData(SQLiteDatabase db, String sql){  
		        Cursor cursor = db.rawQuery(sql,null);  
		        return cursor;  
		    }
	  
}
