package wmi.appl.com;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;

public class BootRecieve extends BroadcastReceiver {

	
	@Override
	public void onReceive(Context context, Intent intent) {
		
	    // TODO Auto-generated method stub
	    Intent myIntent = new Intent(context, DoItAppl.class);
	  //  myIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
	    context.startService(myIntent);
	}
	
}
