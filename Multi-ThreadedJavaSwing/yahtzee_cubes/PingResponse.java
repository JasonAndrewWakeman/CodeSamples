package yahtzee_cubes;

public class PingResponse extends AbstractMessage 
{
	private int originalID;

	 public PingResponse(Component sender) 
	{
		super(sender);
		// TODO Auto-generated constructor stub
	}

	 public PingResponse(Component sender, int originalID) 
	{
		super(sender);
		this.originalID = originalID;
	}

	public int getOriginalID() 
	{
		return this.originalID;
	}

	@Override
	public void dispatch(Component receiver) 
	{
		receiver.handle(this);
	}
}
