package yahtzee_cubes;

import java.util.Random;
import java.util.concurrent.BlockingQueue;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.Executors;
import java.util.concurrent.LinkedBlockingQueue;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

//A Cube's Left side is the region of the screen to the viewer's left side of the cube
//A Cube's Right side is the region of the screen to the viewer's right side of the cube
//. e.g. it is oriented facing away from the viewer. 
/**
 * Component representing a Yahtzee flash cube. Each cube will broadcast a
 * PingMessage to the left and right every POLL_INTERVAL ms. If no reply is
 * received within TIMEOUT ms, the cube assumes it has no neighbor in that
 * direction.
 */
public class Cube extends ThreadedComponent 
{
	public static final int POLL_INTERVAL = 50; // ms
	public static final int TIMEOUT = 250;
	private TimerComponent timer;
	private volatile Status status;
	private volatile int position; // 0 if on the far left through 4 if on far
									// right of a string of cubes
	private volatile boolean vacantLeft;
	private volatile boolean vacantRight;
	private volatile int accumSum;
	private volatile String displayString;
	private volatile int randValue;
	protected BlockingQueue<IMessage> queue;

	protected Thread reader;
	/**
	 * Map of message id as key of requests for which we're waiting for a
	 * result. String is whether it was originally sent left or right
	 */
	private ConcurrentHashMap<Integer, String> pending;

	/**
	 * Map of keys as positions of cubes and values as that cubes randomValue
	 */
	private ConcurrentHashMap<Integer, Integer> values;

	 public Cube(TimerComponent timer) 
	{
		this.timer = timer;
		this.status = Status.START;
		this.position = -1;
		this.displayString = "";
		this.accumSum = 0;
		// the value is a string representing the direction the message was
		// sent and the key was the original message's ID.
		pending = new ConcurrentHashMap<Integer, String>();
		this.vacantLeft = false;
		this.vacantRight = false;
		queue = new LinkedBlockingQueue<IMessage>();
		reader = new Thread(new Reader());
		this.values = new ConcurrentHashMap<Integer, Integer>();
	}

	public void handle(PingResponse msg) 
	{
		// when i receive a response i can remove the entry in the pending
		// hashmap with the id associated with the original ping so that when
		// the
		// corresponding TimeoutMessage arrives nothing will happen.
		int origID = msg.getOriginalID();
		pending.remove(origID);
	}

	public void handle(PingMessage msg) 
	{
		int origID = msg.getId();
		boolean wasSentLeft = msg.isSentLeft();
		// creates a response and returns it to the sender of the PingMessage
		// with the id of the original message which is the key in the original
		// senders pending queue.
		IMessage message = new PingResponse(this, origID);
		msg.sender.send(message);

		// now i can use the sender's position and location relative to me to
		// update my position and the accumSum to update my accumSum
		if (!wasSentLeft) 
		{
			// this came from my left so my position must be 1 + the sender's
			// position
			this.position = msg.getSendersPosition() + 1;
		}
		if (this.status == Status.START) 
		{
			displaySTART(this.position);
			Universe.updateDisplay(this, this.displayString);
		} 
		else if (this.status == Status.GENERATING) 
		{
			// DO NOTHING
		} 
		else if (this.status == Status.SCORING) 
		{

			if (this.position == 0) 
			{
				//put my value in the hashmap
				this.values.put(this.position, this.randValue);
			}
			if (!wasSentLeft) 
			{ // if this message was not originally sent to the left. 
				
				//add each value from the hashmap that came in the message. 
				this.values.putAll(msg.getSendersMap());
				this.values.put(this.position, this.randValue);

				// System.out.println("now i added some stuff and we have " +
				// values );
			}
			Universe.updateDisplay(this, this.displayString);

		
			if (this.position == 4) 	// if this position is 4 then we know we are lined up and we can go
				// to done phase
			{
				
				boolean setToDone = true;
				this.accumSum = 0;
				// check to see if every index in values maps to a value. if it
				// does record the sum and change status to done.
				// synchronize for the entire iteration just in case values for
				// keys 0 and 1 might switch in between accesses.
				synchronized (this.values) 
				{
					for (int i = 0; i < 5; i++) 
					{
						if (values.get(i) == null) 
						{
							setToDone = false;
							break;
						} 
						else
						{
							accumSum = accumSum + values.get(i);
						}
					}
				}
				if (setToDone == true) {
					this.status = Status.DONE; // change the fourth cube's
												// status which will propagate
												// through via ping messages. 
				}
			}

			if (msg.getSendersStatus() == Status.DONE) 
			{
				// then the 4th cube must have computed the overall accumulated
				// sum and i can be done.
				this.status = Status.DONE;
			}
		} else if (this.status == Status.DONE) 
		{
			displayDONE(this.position);
			Universe.updateDisplay(this, this.displayString);
		}

	}

	public void handle(TimeoutMessage msg) 
	{
		int origID = msg.getCorrelationId();
		String wasToMyLeftOrRight = pending.remove(origID);
		
		if (wasToMyLeftOrRight.equals("left")) 
		{  // then we know i sent a message to my left which was not fielded
			this.position = 0;
			this.vacantLeft = true;
		}
		if (wasToMyLeftOrRight.equals("right")) 
		{	// then we know i sent a message to my right which was not fielded
			this.vacantRight = true;
		}
		// in Start Mode
		if (this.status == Status.START) 
		{
			// if vacantLeft and vacantRight are both true then go into
			// generating mode and generate a number for my value.
			if (this.vacantLeft == true && this.vacantRight == true) 
			{
				Random rand = new Random();
				this.randValue = (rand.nextInt(6) + 1);
				this.status = Status.GENERATING;
			}
		}
		// if I am in generating Mode I already know I don't have any neighbor
		// but I need to
		// update my display to be my value and
		// then change to scoring mode and await a ping message from one of my
		// neighbors before updating display again
		else if (this.status == Status.GENERATING) 
		{
			this.displayString = this.randValue + "";
			// probably should only make this call to Universe
			//once instead of twice every ping period.
			Universe.updateDisplay(this, this.displayString);
			this.status = Status.SCORING;
			this.displayString = "?";
		}
		else if (this.status == Status.SCORING) 
		{
			// DO NOTHING
		}
		else if (this.status == Status.DONE) 
		{
			// DO NOTHING
		}
	}

	public void send(IMessage message) {
		this.queue.offer(message);
	}

	private void displaySTART(int pos) {
		if (pos == 0) {
			this.displayString = "S";
		} else if (pos == 1) {
			this.displayString = "T";
		} else if (pos == 2) {
			this.displayString = "A";
		} else if (pos == 3) {
			this.displayString = "R";
		} else if (pos == 4) {
			this.displayString = "T";
		}
	}

	private void displayDONE(int pos) {
		if (pos == 0) {
			this.displayString = "D";
		} else if (pos == 1) {
			this.displayString = "O";
		} else if (pos == 2) {
			this.displayString = "N";
		} else if (pos == 3) {
			this.displayString = "E";
		} else if (pos == 4) {
			this.displayString = this.accumSum + "";
		}
	}

	@Override
	public void start() {
		reader.start();
		sendPingMessages(this, TIMEOUT, timer);
	}

	private int getPos() {
		return this.position;
	}

	private void sendPingMessages(Cube origSender, int timeOutDuration, Component timerComp) {
		final ScheduledExecutorService scheduler = Executors.newScheduledThreadPool(1);

		final Runnable ping = new Runnable() {
			public void run() 
			{
				// when the executor executes (starts) ping, it will
				// periodically send pings containing this Cubes current
				// position in both
				// directions as well as SetTimeoutMessages.

				IMessage msgLeft = new PingMessage(origSender, origSender.getPos(), true, origSender.accumSum,
						origSender.status, origSender.values);
				int idLeft = msgLeft.getId();
				pending.put(idLeft, "left");
				Universe.broadcastLeft(msgLeft);
				SetTimeoutMessage toLeft = new SetTimeoutMessage(origSender, idLeft, TIMEOUT);
				toLeft.dispatch(timer);

				IMessage msgRight = new PingMessage(origSender, origSender.getPos(), false, origSender.accumSum,
						origSender.status, origSender.values);
				int idRight = msgRight.getId();
				pending.put(idRight, "right");
				Universe.broadcastRight(msgRight);
				SetTimeoutMessage toRight = new SetTimeoutMessage((Component) Cube.this, idRight, TIMEOUT);
				toRight.dispatch(timer);

			}
		};
		scheduler.scheduleAtFixedRate(ping, POLL_INTERVAL, POLL_INTERVAL, TimeUnit.MILLISECONDS);

	}

	protected class Reader implements Runnable {
		public void run() 
		{
			while (true) 
			{
				try 
				{
					IMessage message = Cube.this.queue.take();
					message.dispatch(Cube.this);
				} 
				catch (InterruptedException e) 
				{
					log(e.toString());
				} 
				catch (Throwable t) 
				{
					log(t.toString());
				}
			}
		}
	}
}
