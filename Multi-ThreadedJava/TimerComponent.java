package hw3_sample_code;

import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.Executors;
import java.util.concurrent.TimeUnit;

//A Component designed to receive and handle SetTimeoutMessages by starting a timer and after the specified amount of time replying to the original sender with a TimeoutMessage. 
//works by passing the correlation id and original sender to a runnable executable by a scheduled executor.  
public class TimerComponent extends Component {
	

	@Override
	public void send(IMessage message) {
		//do not need 
	}

	@Override
	public void start() {
		//does not need its own thread of execution
	}

	public void doSome(){
		
	}
	public void handle(SetTimeoutMessage msg) {
		// creates a runnable task which is started by an executor service after
		// the set amount of time
		sendReturnMessage(msg.getSender(), msg.getOriginalId(), msg.getTimeout(), this);
	}

	private void sendReturnMessage(Component origSender, int corrID, int timeOutDuration, Component timerComp) {
		final ScheduledExecutorService scheduler = Executors.newScheduledThreadPool(1);

		final Runnable sender = new Runnable() {
			public void run() {
				// when the executor executes (starts) sender, it will dispatch
				// a new TimeoutMessage to the sender to be handled.
				TimeoutMessage to = new TimeoutMessage(corrID, timerComp);
				to.dispatch(origSender);
				

			}
		};
		final ScheduledFuture<?> timeoutHandle = scheduler.schedule(sender, timeOutDuration, TimeUnit.MILLISECONDS);

	}

}
