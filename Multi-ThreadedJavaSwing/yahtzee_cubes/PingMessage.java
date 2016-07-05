package yahtzee_cubes;

import java.util.concurrent.ConcurrentHashMap;

public class PingMessage extends AbstractMessage {

	private int sendersPos;
	private Boolean sentLeft;
	private int sendersValue;
	private Status status;
	private ConcurrentHashMap<Integer, Integer> hm; 

	 public PingMessage(Component sender) 
	{
		super(sender);
		// TODO Auto-generated constructor stub
	}

	 public PingMessage(Component sender, int sendersPos, boolean sentLeft, int value, Status sendersStatus,
			ConcurrentHashMap<Integer, Integer> map) 
	{
		super(sender);
		this.sendersPos = sendersPos;
		this.sentLeft = sentLeft;
		this.sendersValue = value;

		switch (sendersStatus) {
		case START:
			this.status = Status.START;
			break;

		case GENERATING:
			this.status = Status.GENERATING;
			break;

		case SCORING:
			this.status = Status.SCORING;
			break;
		case DONE:
			this.status = Status.DONE;
			break;
		default:

			break;
		}
		
		this.hm = new ConcurrentHashMap<Integer, Integer>(map);

	}
	
 
	public int getSendersPosition() 
	{
		return this.sendersPos;
	}

	public int getSendersValue() 
	{
		return this.sendersValue;
	}

	public boolean isSentLeft() 
	{
		return sentLeft;
	}

	public void handle(Component receiver) 
	{
		receiver.handle(this);
	}

	@Override
	public void dispatch(Component receiver) 
	{
		receiver.handle(this);
	}

	public Status getSendersStatus() 
	{
		return this.status;
	}
	
	public ConcurrentHashMap<Integer, Integer> getSendersMap()
	{
		return this.hm; 
	}

}
